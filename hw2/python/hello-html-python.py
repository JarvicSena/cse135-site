#!/usr/bin/python3
import os
import datetime

print("Content-Type: text/html\n")

name = "Richard and James"
language = "Python"
time = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
ip = os.environ.get("REMOTE_ADDR", "Unknown")

print("<!DOCTYPE html>")
print("<html>")
print("<head>")
print("<meta charset='UTF-8'>")
print("<title>Hello Python World</title>")
print("</head>")
print("<body>")

print(f"<h1>Hello from {name}</h1>")
print("<p>This page was generated with Python</p>")
print(f"<p>This program was generated at: {time}</p>")
print(f"<p>Your current IP Address: {ip}</p>")

print("</body>")
print("</html>")
