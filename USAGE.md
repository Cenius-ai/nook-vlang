# Usage Guide

Once the application is running (see [Installation](INSTALL.md)), you can interact with it through your browser or via HTTP clients.

## Browsing the Site

Visit `http://localhost:8000` in your web browser. The application provides a responsive, light/dark themeable interface.

### Core Features

- **Question Listing**: Browse all questions on the homepage.
- **Ask a Question**: Use the “Ask” button to submit a new question with tags.
- **View Question & Answers**: Click a question to see details, votes, and answers.
- **Answer a Question**: On a question page, write your answer and submit it.
- **Vote**: Upvote or downvote questions and answers.
- **Accept an Answer**: The question author can mark one answer as accepted.
- **Flag Content**: Report inappropriate questions or answers using the flag button.
- **Search**: Use the search bar to find questions or tags.
- **User Profile**: View user profiles with their activity.
- **Notifications**: See notifications for new answers or votes.
- **Admin Moderation**: Moderators (users with the moderator role) can manage flags at `/admin/flags`.

## API / Curl Examples

While the primary interface is web, you can also interact with the underlying HTTP endpoints if you wish (many are POST‑only, requiring CSRF tokens and authentication). Below are illustrative examples using `curl` against the development server.

### Get list of questions

```bash
curl http://localhost:8000/questions
```

### Create a question (requires authentication)

```bash
curl -X POST http://localhost:8000/questions \
  -H "Content-Type: application/json" \
  -d '{"title":"How to center a div?","body":"I need help with CSS","tags":["css","html"]}'
```

### View a specific question

```bash
curl http://localhost:8000/questions/1
```

### Post an answer to a question

```bash
curl -X POST http://localhost:8000/questions/1/answers \
  -H "Content-Type: application/json" \
  -d '{"body": "You can use flexbox or grid."}'
```

### Upvote a question

```bash
curl -X POST http://localhost:8000/questions/1/vote \
  -H "Content-Type: application/json" \
  -d '{"value":1}'
```

### Accept an answer

```bash
curl -X POST http://localhost:8000/answers/5/accept
```

### Flag a question

```bash
curl -X POST http://localhost:8000/questions/1/flags \
  -H "Content-Type: application/json" \
  -d '{"reason":"Spam"}'
```

### Search

```bash
curl http://localhost:8000/search?q=laravel
```

### Get tags

```bash
curl http://localhost:8000/tags
```

### User profile

```bash
curl http://localhost:8000/users/1
```

### Admin flags (moderator access required)

```bash
curl http://localhost:8000/admin/flags
```

> **Note**: These endpoints are illustrative. Actual routes may require authentication, CSRF tokens, and follow RESTful conventions defined in `routes/web.php` and `routes/api.php`. Use the browser with developer tools to inspect exact request formats.