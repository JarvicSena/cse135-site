Members: Richard Baltazar, James Senatin
Grader Login:
        User: grader@142.93.87.52
        Pass: Cse135Winter
Link to domain: https://richardandjames.site/
Github auto deploy
Logging into site:
        User: grader
        Pass: Cse135Winter
changes to HTML file in DevTools after compression:
    After enabling Apache's mode_deflate module, HTML, CSS, and Javascript
    were served in a gzip compression. The network tab in DevTools shows
    Content-Encoding: gzip reponse for these files, indicating that they are
    compressed before sent to the browser
removing 'server' header:
    To hide the server identity, we modified http response headers so that default 
    server info is no longer exposed to public. We installed nginx for reverse proxy 
    to Apache so Nginx controls the final response headers. We installed header-more
    module. Inside /etc/nginx/sites-enables/richardandjames.site we remove the default
    server header and added a custom one called "CSE135 Server."

## robots.txt

Note: All HTTP traffic is redirected by NGINX to the canonical HTTPS domain
`https://richardandjames.site`.

As a result, requests to:
- http://142.93.87.52/robots.txt
- http://localhost/robots.txt

are redirected (301) to:
- https://richardandjames.site/robots.txt

This is expected behavior.

Set-up: Changes can be pushed into GitHub, which automatically are "pulled" by the server as GitHub has our real site, and the live site serves it.
