<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; padding: 0; }
        body { margin: 0; padding: 0; font-family: 'DejaVu Sans', sans-serif; background: #f8fafc; }
        .certificate-wrapper {
            width: 1120px; height: 793px; position: relative;
            background: linear-gradient(135deg, #fff 0%, #f7f4ff 50%, #fff 100%);
            border: 12px solid #6d5ae5; box-sizing: border-box;
        }
        .certificate-inner {
            margin: 30px; padding: 40px; border: 2px solid #e0d8ff;
            height: calc(100% - 60px); box-sizing: border-box;
            text-align: center; position: relative;
        }
        h1 {
            font-size: 38px; color: #6d5ae5; margin-top: 50px; margin-bottom: 10px;
            letter-spacing: 3px; text-transform: uppercase;
        }
        .subtitle { font-size: 14px; color: #94a3b8; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 30px; }
        .presented { font-size: 16px; color: #475569; margin-bottom: 10px; }
        .student-name {
            font-size: 36px; font-weight: bold; color: #1e293b;
            border-bottom: 3px solid #6d5ae5; display: inline-block; padding-bottom: 8px; margin-bottom: 20px;
        }
        .course-info { font-size: 16px; color: #475569; line-height: 1.8; margin-bottom: 30px; }
        .course-title { font-size: 22px; font-weight: bold; color: #6d5ae5; }
        .description { font-size: 13px; color: #64748b; max-width: 600px; margin: 20px auto 0; line-height: 1.6; }
        .date-line { margin-top: 40px; font-size: 13px; color: #94a3b8; }
        .date-line strong { color: #475569; }
        .footer {
            position: absolute; bottom: 30px; left: 0; right: 0;
            text-align: center; font-size: 11px; color: #cbd5e1;
        }
        .seal {
            position: absolute; bottom: 80px; right: 60px;
            width: 80px; height: 80px;
            border: 3px solid #6d5ae5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; color: #6d5ae5; font-weight: bold;
            transform: rotate(-15deg); text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate-inner">
            <h1>Certificate</h1>
            <p class="subtitle">of Completion</p>
            <p class="presented">This is proudly presented to</p>
            <p class="student-name">{{ $student->name }}</p>
            <p class="course-info">
                For successfully completing the course<br>
                <span class="course-title">{{ $course->title }}</span>
            </p>
            @if($certificate->description)
                <p class="description">{{ $certificate->description }}</p>
            @endif
            <p class="date-line">Date issued: <strong>{{ $certificate->created_at->format('F d, Y') }}</strong></p>
            <div class="seal">EduLab</div>
            <p class="footer">EduLab Learning Management System</p>
        </div>
    </div>
</body>
</html>
