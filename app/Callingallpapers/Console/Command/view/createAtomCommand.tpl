<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <author>
        <name>Calling all Papers</name>
    </author>
    <title>Calling all Papers</title>
    <id>http://calingallpapers.com</id>
    <updated>{{"now"|date("c")}}</updated>
    {% for event in events %}
    <entry>
        <title>{{event.name}}</title>
        <link href="{{event.cfp_url}}"/>
        <id>{{event.website_uri}}</id>
        <updated>{{event.cfp_end_date}}</updated>
        <summary>{{event.description}}</summary>
    </entry>
    {% endfor %}
</feed>