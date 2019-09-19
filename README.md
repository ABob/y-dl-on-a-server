# y-dl-on-a-server
Run youtube-dl on your server and let it download and convert videos on it. You then can download or stream the result from there to your local device.

I primarily use it to convert videos to audio files and load it on my smartphone to hear it later and offline (therefore, the interface is mobile friendly written in Bootstrap). There are many sites in the web that offer such a service for you - but they all seemed a little bit shady to me, so I wrote my own solution. 

WARNING: Don't use this at a public web directory! This is a very simple script with no focus on security! Hide it in a private, access restricted place!

FEATURES:
  * Let your server download files from Youtube or other sites that are supported by Youtube-dl
  * Process multiple files with a single request
  * Specify additional arguments for a download, from the whole bandwidth of youtube-dl's command line options
  * List downloaded files and remove them if you don't need them anymore
  * Get status updates of running downloads
  * automatically generated RSS feed to download/watch/hear your downloaded files over a podcast app (for this, I recommend AntennaPod)

INSTALLATION:
To use it, just copy all files/folders in a directory on your web server. You need youtube-dl and php5 (php7 works propably too) to be installed on your server. Also, you have to grant write and read permissions or change ownership of the "dls" and "temp" subfolder so the webserver can write the downloaded files in there (for example with: "chown -R YOUR_WEBSERVER_USER:YOUR_WEBSERVER_USER dls").
The project uses Twitters Bootstrap (and with it: JQuery) to create the website. They are included as files in the repository, but you can link CDN distributed files too, of course.

I have some additional features in mind that will probably be added in the near future, as well as an app for android devices (already in progress) for easier access/handling on smartphones.
