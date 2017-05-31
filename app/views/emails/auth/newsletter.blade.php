<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
    	@if( $unsubscribe )
	        <h2>Unsubscribe Newsletter</h2>
	        <div>
	           Hello, <strong>{{$name}} ({{$email}})</strong> has just UNSUBSCRIBED from our newsletter.
	        </div>
        @else
	        <h2>New Newsletter Subscription</h2>
	        <div>
	           Hello, <strong>{{$name}} ({{$email}})</strong> has just subscribed for our newsletter.
	        </div>
        @endif
    </body>
</html>
