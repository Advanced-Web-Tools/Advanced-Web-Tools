<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>An error has occurred.</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            background-color: #121212;
            color: #2c6d90;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: radial-gradient(circle at center, #1c1c1c 0%, #000 100%);
        }

        .error-box {
            width: 90%;
            max-width: 700px;
            background-color: #1a1a1a;
            border: 2px solid #2c6d90;
            border-radius: 12px;
            box-shadow: 0 0 25px #2c6d90;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .error-summary {
            padding: 24px 30px;
            background-color: #1a1a1a;
        }

        .error-summary h1 {
            margin: 0;
            font-size: 32px;
            letter-spacing: 1px;
            color: #2c6d90;
            text-shadow: 0 0 5px #2c6d90;
        }

        .error-summary p {
            margin: 10px 0 0;
            font-size: 16px;
            color: #ccc;
        }

        .error-details {
            max-height: 0;
            overflow: hidden;
            background-color: #111;
            color: #ffdddd;
            padding: 0 30px;
            transition: max-height 0.4s ease;
        }

        .error-details pre {
            font-size: 14px;
            padding: 20px 0;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .error-box::after {
            content: "▼";
            position: absolute;
            right: 20px;
            top: 25px;
            font-size: 18px;
            color: #2c6d90;
            transition: transform 0.3s ease;
        }

        .error-box.open::after {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
<div class="error-box" id="errorBox">
    <div class="error-summary">
        <h1>SYSTEM ERROR</h1>
        <p>Please try again later.</p>
        <p>If you are an admin, you can enable debug mode in awt_config.php</p>
        <pre>
            const DEBUG = true;
        </pre>
        @if(DEBUG)
            <p>Click to unleash the chaos</p>
        @endif
    </div>
    @if(DEBUG)
        <div class="error-details" id="errorDetails">
      <pre>
        {{ $error }}
      </pre>
        </div>
    @endif
</div>
@if(DEBUG)
    <script>
        const box = document.getElementById('errorBox');
        const details = document.getElementById('errorDetails');

        box.addEventListener('click', () => {
            if (details.style.maxHeight) {
                details.style.maxHeight = null;
                box.classList.remove('open');
            } else {
                details.style.maxHeight = details.scrollHeight + "px";
                box.classList.add('open');
            }
        });
    </script>
@endif
</body>
</html>
