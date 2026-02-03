#!/usr/bin/env python3

import datetime
import html
import os
import sys

server_protocol = os.environ.get("SERVER_PROTOCOL", "")
request_method = os.environ.get("REQUEST_METHOD", "")
query_string = os.environ.get("QUERY_STRING", "")
hostname = os.environ.get("SERVER_NAME", "")
user_agent = os.environ.get("HTTP_USER_AGENT", "")
ip_addr = os.environ.get("REMOTE_ADDR", "")
now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

try:
    content_length = int(os.environ.get("CONTENT_LENGTH", "0"))
except ValueError:
    content_length = 0

body = sys.stdin.read(content_length) if content_length > 0 else ""

print("Content-Type: text/html\n")

print("<!DOCTYPE html>")
print("<html>")
print("<head><title>General Request Echo</title></head>")
print("<body>")
print('<h1 align="center">General Request Echo</h1>')
print("<hr>")

print(f"<p><b>HTTP Protocol: </b>{html.escape(server_protocol)}</p>")
print(f"<p><b>HTTP Method: </b>{html.escape(request_method)}</p>")
print(f"<p><b>Query String: </b>{html.escape(query_string)}</p>")
print(f"<p><b>Time: </b>{html.escape(now)}</p>")
print(f"<p><b>Hostname: </b>{html.escape(hostname)}</p>")
print(f"<p><b>User-Agent Header: </b>{html.escape(user_agent)}</p>")
print(f"<p><b>IP Address: </b>{html.escape(ip_addr)}</p>")
print(f"<p><b>Message Body:</b> {html.escape(body)}</p>")

print("</body>")
print("</html>")
