<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 40px; }
        p { font-size: 20px; }
    </style>
</head>
<body>
    <h1>Certificate of Completion</h1>
    <p>This is to certify that</p>
    <h2>{{ $student->full_name }}</h2>
    <p>has successfully completed the course</p>
    <h2>{{ $course->name_en }}</h2>
    <p>with a final mark of {{ $finalMark }}/100</p>
    <p>Date: {{ now()->format('d M Y') }}</p>
</body>
</html>
