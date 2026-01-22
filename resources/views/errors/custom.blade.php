<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Something Went Wrong</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/vertical-light-layout/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
        }
        .debug-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .error-header {
            background: #dc3545;
            color: #fff;
            padding: 15px;
            border-radius: 5px 5px 0 0;
        }
        .error-details {
            background: #fff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .stack-trace {
            max-height: 400px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .stack-frame {
            margin-bottom: 10px;
        }
        .btn-home {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="debug-container">
    <div class="error-header">
        <h1>{{ $exception->getCode() !== 0 ? $exception->getCode() : 500 }}</h1>
        <h5>{{ $exception->getMessage() ?: 'An unexpected error occurred.' }}</h5>
    </div>
    <div class="error-details">
        <h3>Error Details</h3>
        <p><strong>Type:</strong> {{ get_class($exception) }}</p>
        <p><strong>File:</strong> {{ $exception->getFile() }}</p>
        <p><strong>Line:</strong> {{ $exception->getLine() }}</p>
        <h4 class="mt-4">Stack Trace</h4>
        <div class="stack-trace">
            @foreach ($exception->getTrace() as $index => $frame)
                <div class="stack-frame">
                    <strong>#{{ $index }}</strong>
                    <p>
                        {{ isset($frame['file']) ? $frame['file'] : '[Internal Function]' }}
                        {{ isset($frame['line']) ? ':'.$frame['line'] : '' }}
                        <br>
                        {{ isset($frame['class']) ? $frame['class'].$frame['type'] : '' }}{{ $frame['function'] }}()
                    </p>
                </div>
            @endforeach
        </div>
        <a href="{{ url('/') }}" class="btn btn-primary btn-home">Return to Homepage</a>
    </div>
</div>


<!-- Bootstrap 4 JS (Optional, if you need JS features) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
