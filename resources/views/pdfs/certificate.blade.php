<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: landscape; margin: 0; padding: 0; }
        * { margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', serif; background: #fff; }
        .certificate-wrapper {
            width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #ffffff 0%, #f7f4ff 50%, #ffffff 100%);
            padding: 0; margin: 0;
        }
        .certificate-container {
            width: 95%; max-width: 1200px; height: 95vh; max-height: 700px;
            background: linear-gradient(135deg, #fff 0%, #f7f4ff 50%, #fff 100%);
            border: 15px solid #6d5ae5; box-sizing: border-box; position: relative;
            display: flex; flex-direction: column; justify-content: space-between; padding: 50px;
        }
        .certificate-header {
            text-align: center; margin-bottom: 20px;
        }
        h1 {
            font-size: 52px; color: #6d5ae5; letter-spacing: 4px; text-transform: uppercase;
            font-weight: bold; margin-bottom: 5px;
        }
        .subtitle { font-size: 18px; color: #94a3b8; letter-spacing: 3px; text-transform: uppercase; }
        .certificate-body {
            text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center;
        }
        .presented { font-size: 18px; color: #475569; margin-bottom: 15px; }
        .student-name {
            font-size: 48px; font-weight: bold; color: #1e293b; letter-spacing: 2px;
            border-bottom: 4px solid #6d5ae5; display: inline-block; padding-bottom: 12px; margin: 0 auto 25px;
        }
        .course-info {
            font-size: 18px; color: #475569; line-height: 1.8; margin-bottom: 15px;
        }
        .course-title { font-size: 26px; font-weight: bold; color: #6d5ae5; margin-top: 8px; }
        .description { font-size: 15px; color: #64748b; max-width: 700px; margin: 20px auto; line-height: 1.6; }
        .date-line { font-size: 14px; color: #64748b; margin-top: 15px; }
        .date-line strong { color: #475569; }
        .certificate-footer {
            text-align: center; margin-top: 30px; display: flex; justify-content: space-around; align-items: flex-end;
        }
        .signature-line { border-top: 2px solid #6d5ae5; width: 150px; text-align: center; }
        .signature-label { font-size: 12px; color: #64748b; margin-top: 5px; }
        .seal {
            width: 100px; height: 100px; border: 3px solid #6d5ae5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 11px; color: #6d5ae5;
            font-weight: bold; transform: rotate(-15deg); text-align: center; text-transform: uppercase;
        }
        .footer-text { font-size: 12px; color: #cbd5e1; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate-container">
            <div class="certificate-header">
                <h1>Certificate</h1>
                <p class="subtitle">of Completion</p>
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
                <div>
                    <div class="signature-line"></div>
                    <p class="signature-label">Director</p>
                </div>
                <div class="seal">
                    <div>EduLab<br>Certificate</div>
                </div>
                <div>
                    <div class="signature-line"></div>
                    <p class="signature-label">Instructor</p>
                </div>
            </div>
            <p class="footer-text">EduLab Learning Management System | Certificate ID: {{ $certificate->id }}</p>
        </div>
    </div>
</body>
</html>
