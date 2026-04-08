<!DOCTYPE html>
<html>
<head>
  <title>API Docs</title>
  <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
</head>
<body>
  <div id="app"></div>

  <script>
    Scalar.createApiReference('#app', {
      url: '{{ url("/docs/openapi.yaml") }}',
      theme: 'purple',
    })
  </script>
</body>
</html>