# rg-mailing
Mailing for Rozental Group and partner platforms

A little application that provides email notification.

For using this application you need:
1. beanstalkd server
1. email server (exim4 in my case)
1. composer
1. systemd service definition (optional)
1. firewall settings (optional)

# Input format

> {  
>	 "destination": [
>		 "name1@example.com",
>		 "name2@example.com"
>	 ],
>	 "theme": "Example",
>	 "content": "Example content",
>	 "attachment": [
>		 "http://example.com/file.jpg",
>		 "http://example.com/file.odt"
>	 ]
> }
