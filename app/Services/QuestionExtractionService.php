<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

class QuestionExtractionService
{
    public function extract(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $text = match ($extension) {
            'pdf' => $this->extractTextFromPdf($file),
            'docx' => $this->extractTextFromDocx($file),
            'doc' => $this->extractTextFromDoc($file),
            'txt' => $file->getContent(),
            default => throw new \InvalidArgumentException('Unsupported file type: ' . $extension),
        };

        return $this->parseQuestions($text);
    }

    private function extractTextFromPdf(UploadedFile $file): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseContent($file->get());
        return $pdf->getText();
    }

    private function extractTextFromDocx(UploadedFile $file): string
    {
        $tempPath = $file->getPathname();
        $phpWord = WordIOFactory::load($tempPath);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
                if (method_exists($element, 'getElements')) {
                    foreach ($element->getElements() as $child) {
                        if (method_exists($child, 'getText')) {
                            $text .= $child->getText() . "\n";
                        }
                    }
                }
            }
        }
        return $text;
    }

    private function extractTextFromDoc(UploadedFile $file): string
    {
        $tempPath = $file->getPathname();
        $phpWord = WordIOFactory::load($tempPath);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }
        return $text;
    }

    public function parseQuestions(string $text): array
    {
        $lines = collect(explode("\n", $text))
            ->map(fn($l) => trim($l))
            ->filter(fn($l) => $l !== '')
            ->values()
            ->toArray();

        $questions = [];
        $current = null;

        foreach ($lines as $line) {
            $matched = false;

            if ($parsed = $this->matchStructuredQuestion($line, $lines, $current)) {
                if ($current !== null) {
                    $questions[] = $current;
                }
                $current = $parsed;
                $matched = true;
            } elseif ($current && $this->matchOptionLine($line, $current)) {
                $matched = true;
            } elseif ($current && $this->matchCorrectAnswerLine($line, $current)) {
                $matched = true;
            } elseif ($current && $this->matchTrueFalseBody($line, $current)) {
                $matched = true;
            }

            if (!$matched && $current) {
                $current['body'] .= ' ' . $line;
            }
        }

        if ($current !== null) {
            $questions[] = $current;
        }

        if (empty($questions)) {
            $questions = $this->fallbackLineByLine($lines);
        }

        return $this->normalizeQuestions($questions);
    }

    private function matchStructuredQuestion(string $line, array &$lines, ?array $current): ?array
    {
        // Q1. or 1. or Question 1: pattern at start of line
        if (preg_match('/^(?:Q(?:uestion)?[.\s]*)?(\d+)[.)]\s+(.+)/i', $line, $m)) {
            return [
                'question' => $m[2],
                'type' => 'multiple_choice',
                'options' => [],
                'correct_answer' => null,
                'marks' => 1,
            ];
        }

        // True/False: sentence? pattern
        if (preg_match('/^(?:Q(?:uestion)?[.\s]*)?(\d+)[.)]\s*(.+)\?\s*$/i', $line, $m)) {
            $nextLines = array_slice($lines, array_search($line, $lines) + 1, 3);
            $nextText = implode(' ', $nextLines);
            if (preg_match('/\b(True|False|Yes|No)\b/i', $nextText)) {
                return [
                    'question' => $m[2],
                    'type' => 'true_false',
                    'options' => ['True', 'False'],
                    'correct_answer' => null,
                    'marks' => 1,
                ];
            }
        }

        return null;
    }

    private function matchOptionLine(string $line, array &$current): bool
    {
        if (preg_match('/^([A-Z])[.)]\s+(.+)/', $line, $m)) {
            $current['options'][] = ['letter' => $m[1], 'text' => $m[2]];
            return true;
        }
        return false;
    }

    private function matchCorrectAnswerLine(string $line, array &$current): bool
    {
        if (preg_match('/^(?:Correct|Answer|Ans)[:\s]*(.+)/i', $line, $m)) {
            $answer = trim($m[1]);
            if (preg_match('/^[A-Z]$/i', $answer)) {
                $letter = strtoupper($answer);
                foreach ($current['options'] as $opt) {
                    if ($opt['letter'] === $letter) {
                        $current['correct_answer'] = $opt['text'];
                        break;
                    }
                }
            } else {
                $current['correct_answer'] = $answer;
            }
            return true;
        }

        if (preg_match('/^(?:Correct|Answer|Ans)[:\s]*\(?([A-Z])\)?/i', $line, $m)) {
            $letter = strtoupper($m[1]);
            foreach ($current['options'] as $opt) {
                if ($opt['letter'] === $letter) {
                    $current['correct_answer'] = $opt['text'];
                    break;
                }
            }
            return true;
        }

        return false;
    }

    private function matchTrueFalseBody(string $line, array &$current): bool
    {
        if ($current['type'] === 'true_false') {
            if (preg_match('/^(?:Correct|Answer|Ans)[:\s]*(.+)/i', $line, $m)) {
                $answer = trim(strtolower($m[1]));
                if (in_array($answer, ['true', 'false', 'yes', 'no'])) {
                    $current['correct_answer'] = ucfirst($answer);
                }
                return true;
            }
        }
        return false;
    }

    private function fallbackLineByLine(array $lines): array
    {
        $questions = [];

        foreach ($lines as $line) {
            if (preg_match('/^(?:Q(?:uestion)?[.\s]*)?(\d+)[.)]\s+(.+)/i', $line, $m)) {
                $questions[] = [
                    'question' => $m[2],
                    'type' => 'multiple_choice',
                    'options' => [],
                    'correct_answer' => null,
                    'marks' => 1,
                ];
            } elseif (!empty($questions) && preg_match('/^([A-E])[.)]\s+(.+)/', $line, $m)) {
                $idx = count($questions) - 1;
                $questions[$idx]['options'][] = ['letter' => $m[1], 'text' => $m[2]];
            } elseif (!empty($questions) && preg_match('/^(?:Correct|Answer|Ans)[:\s]*\(?([A-E])\)?/i', $line, $m)) {
                $idx = count($questions) - 1;
                $letter = strtoupper($m[1]);
                foreach ($questions[$idx]['options'] as $opt) {
                    if ($opt['letter'] === $letter) {
                        $questions[$idx]['correct_answer'] = $opt['text'];
                        break;
                    }
                }
            }
        }

        return $questions;
    }

    private function normalizeQuestions(array $questions): array
    {
        return array_map(function ($q) {
            $options = [];
            $hasOptions = !empty($q['options']);

            if ($q['type'] === 'multiple_choice' && $hasOptions) {
                foreach ($q['options'] as $opt) {
                    $options[] = $opt['letter'] . '. ' . $opt['text'];
                }
            } elseif ($q['type'] === 'true_false' || $q['type'] === 'multiple_select') {
                $options = $q['options'];
            }

            if ($q['type'] === 'true_false' && empty($options)) {
                $options = ['True', 'False'];
            }

            if (!in_array($q['type'], ['multiple_choice', 'multiple_select', 'true_false', 'short_answer', 'essay'])) {
                $q['type'] = 'multiple_choice';
            }

            if ($q['type'] === 'true_false' && $q['correct_answer']) {
                $q['correct_answer'] = ucfirst(strtolower($q['correct_answer']));
            }

            return [
                'question' => $q['question'],
                'type' => $q['type'],
                'options' => $options,
                'correct_answer' => $q['correct_answer'],
                'marks' => max(1, (int) $q['marks']),
            ];
        }, $questions);
    }
}
