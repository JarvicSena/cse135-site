#!/usr/bin/env python3

import os
import html

env_keys = sorted(os.environ.keys())

print("Content-Type: text/html\n")

print("""<!DOCTYPE html>
<html>
<head>
<title>Environment Variables</title>
</head>
<body>
<h1 align="center">Environment Variables</h1>
<hr>
""")

for key in env_keys:
    value = os.environ.get(key, "")
    print(f"<b>{html.escape(key)}:</b> {html.escape(value)}<br />")

print("""
</body>
</html>
""")
