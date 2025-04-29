<!-- resources/views/emails/new_post.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>New Post Notification</title>
</head>
<body>
<h1>New Post Created!</h1>
<p>Title: {{ $post->title }}</p>
<p>Content: {{ $post->content }}</p>
</body>
</html>
