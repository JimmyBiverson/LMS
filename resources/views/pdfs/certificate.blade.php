<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 landscape; margin: 0; padding: 0; }
        * { box-sizing: border-box; }
        html, body {
            margin: 0; padding: 0; width: 100%; height: 100%;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #f5f7ff;
            color: #1f2937;
        }
        body {
            display: flex; align-items: center; justify-content: center;
            padding: 18px;
        }
        .certificate-wrapper {
            position: relative; width: 100%; height: 100%; min-height: 100vh;
            background: linear-gradient(135deg, #f4f0ff 0%, #ffffff 20%, #eef8ff 100%);
            overflow: hidden;
            border: 12px solid #1f2a44;
        }
        .strip {
            position: absolute; inset: 0;
            background:
                linear-gradient(90deg, rgba(89, 75, 204, 0.12) 0 18%, transparent 18% 38%, rgba(89, 75, 204, 0.08) 38% 42%, transparent 42% 100%),
                linear-gradient(180deg, rgba(17, 24, 39, 0.04) 0 100%);
        }
        .certificate-container {
            position: relative; z-index: 1;
            width: calc(100% - 48px); height: calc(100% - 48px);
            margin: 24px auto;
            background: rgba(255, 255, 255, 0.86);
            border: 4px solid #5a46db;
            box-shadow: inset 0 0 0 10px rgba(90, 70, 219, 0.12);
            padding: 48px 60px 28px;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .top-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding-bottom: 20px; border-bottom: 2px solid rgba(90, 70, 219, 0.18);
        }
        .brand {
            display: flex; align-items: center; gap: 14px;
            font-size: 14px; letter-spacing: 3px; text-transform: uppercase; color: #5a46db; font-weight: bold;
        }
        .brand-mark {
            width: 46px; height: 46px; border-radius: 14px; background: linear-gradient(135deg, #5a46db, #7c6ae8);
            color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold;
        }
        .certificate-header { text-align: center; margin-top: 26px; }
        .eyebrow {
            font-size: 15px; letter-spacing: 0.42em; text-transform: uppercase; color: #7c8aa5; font-weight: bold;
            margin-bottom: 10px;
        }
        h1 {
            margin: 0; font-size: 52px; line-height: 1.1; letter-spacing: 4px; text-transform: uppercase; color: #1f2a44; font-weight: 700;
        }
        .subtitle {
            margin-top: 8px; font-size: 18px; letter-spacing: 0.34em; text-transform: uppercase; color: #5a46db; font-weight: 600;
        }
        .certificate-body {
            text-align: center; margin-top: 10px; flex: 1; display: flex; flex-direction: column; justify-content: center;
        }
        .presented { font-size: 18px; color: #475569; margin-bottom: 16px; }
        .student-name {
            display: inline-block; margin: 0 auto 20px auto; padding: 0 22px 10px; font-size: 42px; line-height: 1.1;
            font-weight: 800; color: #1f2937; letter-spacing: 2px; border-bottom: 4px solid #5a46db;
        }
        .course-info { font-size: 18px; color: #475569; line-height: 1.7; }
        .course-title {
            font-size: 24px; color: #5a46db; font-weight: 800; margin-top: 6px;
        }
        .description {
            margin: 18px auto 0; max-width: 650px; font-size: 15px; line-height: 1.6; color: #64748b; font-style: italic;
        }
        .date-line {
            margin-top: 18px; font-size: 14px; color: #64748b; font-weight: 600;
        }
        .date-line strong { color: #1f2937; }
        .certificate-footer {
            display: flex; justify-content: space-between; align-items: flex-end; margin-top: 28px; padding-top: 8px;
            border-top: 2px solid rgba(90, 70, 219, 0.18);
        }
        .signature-box {
            width: 210px; text-align: center;
        }
        .signature-line {
            border-top: 3px solid #5a46db; width: 160px; margin: 0 auto 8px auto;
        }
        .signature-label {
            font-size: 12px; letter-spacing: 0.2em; text-transform: uppercase; color: #64748b; font-weight: 700;
        }
        .seal {
            width: 110px; height: 110px; border-radius: 50%; border: 4px solid #5a46db; color: #5a46db;
            display: flex; align-items: center; justify-content: center; font-size: 11px; line-height: 1.3; text-align: center;
            font-weight: 800; letter-spacing: 1px; transform: rotate(-14deg); background: rgba(90, 70, 219, 0.04);
        }
        .footer-text {
            margin-top: 18px; text-align: center; font-size: 12px; color: #94a3b8; letter-spacing: 0.1em; text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="strip"></div>
        <div class="certificate-container">
            <div class="top-bar">
                <div class="brand">
                    <div class="brand-mark">L</div>
                    <span>Edulab</span>
                </div>
                <div class="brand" style="color:#7c8aa5; letter-spacing:0.28em;">Verified Learning</div>
            </div>

            <div class="certificate-header">
                <div class="eyebrow">Official</div>
                <h1>Certificate</h1>
                <div class="subtitle">of Completion</div>
            </div>

            <div class="certificate-body">
                <p class="presented">This is proudly presented to</p>
                <p class="student-name">{{ strtoupper($student->name) }}</p>
                <div class="course-info">
                    <p>For successfully completing the course</p>
                    <p class="course-title">{{ $course->title }}</p>
                </div>
                @if($certificate->description)
                    <p class="description">"{{ $certificate->description }}"</p>
                @endif
                <p class="date-line">Issued on <strong>{{ $certificate->created_at->format('F d, Y') }}</strong></p>
            </div>

            <div class="certificate-footer">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Director</div>
                </div>
                <div class="seal">EduLab<br>Certified</div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Instructor</div>
                </div>
            </div>

            <div class="footer-text">EduLab Learning Management System | Certificate ID: {{ $certificate->id }}</div>
        </div>
    </div>
</body>
</html>
