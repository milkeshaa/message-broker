# Message broker BE challenge
This is a repository containing Message broker BE challenge for Heartpace.
Clone it and start using it.

# Challenge
**Task:** Service which gets message from a queue (Redis or SQS/SNS) and sends out based on the template. Each channel should have its own queue.Message is described (but not limited to) by next attributes:

- channel (email/sms/slack/teams/webhook…etc)
- type (layout, ex. birthday digest/reminder/invitation with a link…etc)
- body (text or params containing message body)

Log function should reflect status of the outgoing message. In case of failure: 3 retries are available.Tech stack:

- laravel 10
- php 8.2
- docker-compose

In order to test the function console command can be used, which receives channel | type | body arguments.It is allowed to use trusted libs, ex https://laravel-notification-channels.com/https://laravel.com/docs/10.x/notifications

## Start the services
+ Clone repository to your working directory.
+ Create .env file with command:
```sh
        $ cp ./src/.env.example ./src/.env
```        
+ Replace your MAIL_* credentials in .env file (for *email* channel testing) with mailtrap ones in a way:
```
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=***c605c527de1 #here paste your Mailtrap username
    MAIL_PASSWORD=***cd851d79a3f #here paster your Mailtrap password
```
+ Please double-check if DB_HOST & REDIS_HOST are as you expect them to be (by default I'm setting containers names, but maybe your system requires ip address instead)
+ Install the dependencies:
```sh
        $ docker-compose run --rm composer install
```
+ Run the following command to make services up and running:
```sh
        $ docker-compose up -d --build
```
+ To run the migrations (for failed_jobs & messages tables):
```sh
        $ docker-compose run artisan migrate
```
## Notes
+ You don't need to start queues for different channels, since it's already handled in the docker-compose file with containers.
+ Each channel (*email*, *webhook*, *sms*, *telegram*, *slack*, ...) has its own log file in /src/storage/logs. 
+ Each time command is executed and message is created & sent you should see new logs in the corresponding log file.
+ Logging is handled by events & failed method for Notifications.
+ Notification will be retried 3 times in total if failed.
+ I didn't implement functionality of sending message to SMS, Slack, Telegram channels, because to test them 
  I have either to use some service (sms), or to create chatbots.
  I showed the idea of how I see the solution for the task in *Channel.php classes (SmsChannel.php, TelegramChannel.php, ...),
  we can create our custom handlers or use the available from https://laravel-notification-channels.com/, but the idea is the same.
  All the channels are running, but sms, telegram & slack will just do dry runs any time you send the message.
  If I correctly understood the requirement of the task - I have to show the idea how messages can be handled, and not implement the actual handling.
+ Of course for email & webhook channels I've implemented custom handling logic, because for them no 3rd party api is required, and it's easy to handle that by your own code :)
## Usage of the command
+ Use the command to send the message to redis:
```sh
        $ docker-compose run artisan message:push {channel} {type} {body} {metadata?*}
```
+ Command has the description and rules so feel free to check the code on the PushMessage.php class.
+ You can use only *email*, *webhook*, *sms*, *telegram*, *slack* channels.
+ You can use only *invitation*, *reminder*, *birthday_digest* types.
+ You can use command like that as well (metadata will be skipped, but prompt will appear for channel, type & body args):
```sh
        $ docker-compose run artisan message:push
```
+ It was not written in the task, but it feels like we should know the receiver for email & webhook & sms, so I decided to ask for that as well if these channels are chosen.
+ For *email* channel it should be email address (otherwise sending of the message out will fail and will be logged in the corresponding file)
+ For *webhook* channel it should be proper url (otherwise sending of the message out will fail and will be logged in the corresponding file)
+ To validate if the message was handled by the proper queue you can get the logs of the expected container:
```sh
        $ docker-compose logs email-queue
        $ docker-compose logs webhook-queue
        $ docker-compose logs sms-queue
        $ docker-compose logs telegram-queue
        $ docker-compose logs slack-queue
```
+ To check the logs of the Notification processing feel free to open any of these files (will be created if you try to use corresponding channel):
```
    for email - /src/storage/logs/email-channel-laravel.log
    for webhook - /src/storage/logs/webhook-channel-laravel.log
    for sms - /src/storage/logs/sms-channel-laravel.log
    for telegram - /src/storage/logs/telegram-channel-laravel.log
    for slack - /src/storage/logs/slack-channel-laravel.log
```
+ To check if message was actually sent out feel free to jump to your Mailtrap inbox (for *email* channel) or to provided URL server logs (for *webhook* channel).
