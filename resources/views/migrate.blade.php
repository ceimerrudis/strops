<!DOCTYPE html>
<html>
<head>
    <title>Migration Runner</title>

    <style>
        body {
            font-family: monospace;
            background: #111;
            color: #0f0;
            padding: 20px;
        }

        #output {
            white-space: pre-wrap;
            border: 1px solid #333;
            padding: 15px;
            height: 80vh;
            overflow-y: auto;
            background: #000;
        }

        .status {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="status">
    Status:
    <span id="status">Starting...</span>
</div>

<div id="output"></div>

<script>
    async function fetchOutput() {
        try {
            const response = await fetch('/migrate-status');
            const data = await response.json();

            document.getElementById('output').textContent = data.output;

            const outputDiv = document.getElementById('output');
            outputDiv.scrollTop = outputDiv.scrollHeight;

            document.getElementById('status').textContent =
                data.running ? 'Running...' : 'Finished';

            if (data.running) {
                setTimeout(fetchOutput, 1000);
            }

        } catch (e) {
            document.getElementById('status').textContent =
                'Error: ' + e.message;
        }
    }

    fetchOutput();
</script>

</body>
</html>
