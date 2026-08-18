# LMS API Documentation

## Overview
This document outlines the API endpoints for the Learning Management System (LMS), including authentication, course management, assignments, quizzes, and user profiles.

---

## 🔐 Authentication Endpoints

### Login
- **POST** `/login`
- **Parameters**: `email`, `password`, `remember` (optional)
- **Response**: Redirect to dashboard or error message
- **Used by**: All user roles (student, instructor, organization, admin)

### Logout
- **POST** `/logout`
- **Response**: Redirect to homepage
- **Auth Required**: Yes

### Register
- **POST** `/register`
- **Parameters**: `name`, `email`, `password`, `password_confirmation`, `role`
- **Response**: Redirect to login or error
- **Auth Required**: No

### Password Reset
- **POST** `/forgot-password`
- **Parameters**: `email`
- **Response**: Email with reset link

---

## 👤 Profile & Settings Endpoints

### Student Profile Settings
- **GET** `/dashboard/settings`
- **POST** `/dashboard/settings`
- **Parameters**: `first_name`, `last_name`, `email`, `bio` (optional)
- **Auth Required**: Yes (Student)

### Student Password Change
- **POST** `/dashboard/settings`
- **Parameters**: `current_password`, `password`, `password_confirmation`
- **Validation**: Current password must match, new password min 8 chars
- **Response**: Redirect with success/error message
- **Auth Required**: Yes (Student)

### Instructor Profile Settings
- **GET** `/instructor/settings`
- **POST** `/instructor/settings`
- **Parameters**: `first_name`, `last_name`, `email`, `designation`
- **Auth Required**: Yes (Instructor)

### Instructor Password Change
- **POST** `/instructor/change-password`
- **Parameters**: `current_password`, `password`, `password_confirmation`
- **Validation**: 'current_password' rule, password min 8 chars with confirmation
- **Response**: Redirect with status message
- **Auth Required**: Yes (Instructor)

### Organization Profile Settings
- **GET** `/org/settings`
- **POST** `/org/settings`
- **Parameters**: `name`, `email`, `phone`, `address`
- **Auth Required**: Yes (Organization)

### Organization Password Change
- **POST** `/org/change-password`
- **Parameters**: `current_password`, `password`, `password_confirmation`
- **Validation**: 'current_password' rule, password min 8 chars with confirmation
- **Response**: Redirect with status message
- **Auth Required**: Yes (Organization)

---

## 📚 Course Endpoints

### List Courses
- **GET** `/courses`
- **Query Parameters**: `category`, `search`, `level`
- **Response**: HTML page with course listings
- **Auth Required**: No

### View Course Details
- **GET** `/courses/{slug}`
- **Response**: HTML page with course info, instructor details, enrollment status
- **Auth Required**: No

### Enroll in Course
- **POST** `/courses/{courseId}/enroll`
- **Response**: Redirect with success message or error (if already enrolled)
- **Auth Required**: Yes (Student)

---

## 📝 Assignment Endpoints

### List Assignments (Student)
- **GET** `/dashboard/assignments`
- **Response**: Assignments for enrolled courses only
- **Auth Required**: Yes (Student)
- **Filtering**: Only enrolled courses

### Submit Assignment
- **POST** `/student/assignment/{assignmentId}/submit`
- **Parameters**: `answer` (text), `file` (optional), `user_id` (auto-filled)
- **Validation**: Enrollment check required, student must be enrolled in course
- **Response**: Redirect with success message
- **Auth Required**: Yes (Student)
- **Enrollment Check**: ✅ Enabled

### List Assignments (Instructor)
- **GET** `/instructor/course/{courseId}/assignments`
- **Response**: All assignments for instructor's course
- **Auth Required**: Yes (Instructor)

### Create Assignment
- **POST** `/instructor/assignments`
- **Parameters**: `title`, `description`, `course_id`, `instructions_file` (optional), `due_date`
- **File Upload**: instructions_file stored in `assignments/instructions`
- **Auth Required**: Yes (Instructor)

### Update Assignment
- **POST** `/instructor/assignments/{assignmentId}/update`
- **Parameters**: `title`, `description`, `due_date`, `instructions_file` (optional)
- **File Management**: Old file deleted if new file uploaded
- **Auth Required**: Yes (Instructor)

### Delete Assignment
- **POST** `/instructor/assignments/{assignmentId}/delete`
- **Response**: Redirect with success message
- **Auth Required**: Yes (Instructor)

### Download Assignment Instructions
- **GET** `/assignments/{assignmentId}/download-instructions`
- **Response**: File download
- **Auth Required**: Yes (Student if enrolled)

---

## 🧪 Quiz Endpoints

### List Quizzes (Student)
- **GET** `/dashboard/quizzes`
- **Response**: Quizzes for enrolled courses only
- **Auth Required**: Yes (Student)
- **Filtering**: Only enrolled courses

### Take Quiz
- **GET** `/quizzes/{quizId}/take`
- **Response**: Quiz interface with questions
- **Auth Required**: Yes (Student)
- **Enrollment Check**: ✅ Enabled - Student must be enrolled
- **Attempt Tracking**: Counts attempts per student

### Submit Quiz
- **POST** `/quizzes/{quizId}/submit`
- **Parameters**: `answers` (array of question_id => answer), `user_id` (auto-filled)
- **Response**: Score, pass/fail status, redirect
- **Auth Required**: Yes (Student)
- **Scoring**: Automatic calculation based on correct answers

### List Quizzes (Instructor)
- **GET** `/instructor/course/{courseId}/quizzes`
- **Response**: All quizzes for instructor's course
- **Auth Required**: Yes (Instructor)

### Create Quiz
- **POST** `/instructor/quizzes`
- **Parameters**: `title`, `description`, `course_id`, `passing_score`, `instructions_file` (optional)
- **File Upload**: instructions_file stored in `quizzes/instructions`
- **Auth Required**: Yes (Instructor)

### Update Quiz
- **POST** `/instructor/quizzes/{quizId}/update`
- **Parameters**: `title`, `description`, `passing_score`, `instructions_file` (optional)
- **File Management**: Old file deleted if new file uploaded
- **Auth Required**: Yes (Instructor)

### Delete Quiz
- **POST** `/instructor/quizzes/{quizId}/delete`
- **Response**: Redirect with success message
- **Auth Required**: Yes (Instructor)

### Add Quiz Question
- **POST** `/instructor/quiz/{quizId}/questions`
- **Parameters**: `question`, `options` (array), `correct_answer`, `marks`, `type`
- **Auth Required**: Yes (Instructor)

### Download Quiz Instructions
- **GET** `/quizzes/{quizId}/download-instructions`
- **Response**: File download
- **Auth Required**: Yes (Student if enrolled)

---

## 🎫 Support Tickets Endpoints

### Create Support Ticket
- **POST** `/support-tickets`
- **Parameters**: `subject`, `message`, `category`, `priority`
- **Notifications**: ✅ SupportTicketCreated notification sent to instructors/admins
- **Auth Required**: Yes

### View Support Ticket
- **GET** `/support-tickets/{ticketId}`
- **Response**: Ticket details with all replies
- **Auth Required**: Yes (Author or Admin/Instructor)

### Reply to Support Ticket
- **POST** `/support-tickets/{ticketId}/reply`
- **Parameters**: `message`
- **Notifications**: ✅ SupportTicketReply notification sent to ticket watchers
- **Auth Required**: Yes

### List Support Tickets
- **GET** `/support-tickets`
- **Response**: User's tickets or all tickets (admin)
- **Auth Required**: Yes

---

## 📊 Notification Endpoints

### Get Notifications
- **GET** `/notifications`
- **Response**: User's notification list
- **Auth Required**: Yes

### Mark Notification as Read
- **POST** `/notifications/{notificationId}/read`
- **Auth Required**: Yes

### Set Notification Preferences
- **POST** `/notification-preferences`
- **Parameters**: `preferences` (array of notification types)
- **Auth Required**: Yes

---

## 🔒 Security Features

### CSRF Protection
- All POST/PUT/DELETE requests require CSRF token
- Token auto-included in form `@csrf`

### Role-Based Access Control
- **Student**: Can only access own data
- **Instructor**: Can manage own courses and student submissions
- **Organization**: Can manage organization-level settings
- **Admin**: Can access all resources

### Enrollment Filtering
✅ **Active on**:
- Assignment submission routes
- Quiz taking and submission routes
- Students can only submit work for enrolled courses

---

## 📁 File Upload Endpoints

### Assignment Instructions Upload
- **Endpoint**: `/instructor/assignments` (via form)
- **Storage Path**: `storage/app/public/assignments/instructions/`
- **Supported Formats**: PDF, DOC, DOCX, TXT, etc.
- **Max Size**: Configured in `.env` (default 10MB)

### Quiz Instructions Upload
- **Endpoint**: `/instructor/quizzes` (via form)
- **Storage Path**: `storage/app/public/quizzes/instructions/`
- **Supported Formats**: PDF, DOC, DOCX, TXT, etc.
- **Max Size**: Configured in `.env` (default 10MB)

### Assignment Submission Files
- **Endpoint**: `/student/assignment/{assignmentId}/submit` (via form)
- **Storage Path**: `storage/app/public/submissions/`
- **Max Size**: Configured in `.env` (default 10MB)

---

## 🚀 Response Format

### Success Response (HTML Redirect)
```
302 Redirect to /previous-page or next page
Session Flash: success message
```

### Success Response (JSON - if implemented)
```json
{
  "success": true,
  "message": "Operation completed",
  "data": {}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": "error message"
  }
}
```

---

## 🔄 Workflow Examples

### Student Course Enrollment & Assignment Submission
1. Browse courses: `GET /courses`
2. View course: `GET /courses/{slug}`
3. Enroll: `POST /courses/{courseId}/enroll`
4. View assignments: `GET /dashboard/assignments`
5. Download instructions: `GET /assignments/{assignmentId}/download-instructions`
6. Submit assignment: `POST /student/assignment/{assignmentId}/submit`

### Instructor Course & Quiz Management
1. Create course: `POST /instructor/courses`
2. Create quiz: `POST /instructor/quizzes`
3. Add questions: `POST /instructor/quiz/{quizId}/questions`
4. View quiz: `GET /instructor/quizzes/{quizId}`
5. Check student results: `GET /instructor/quiz/{quizId}/results`

### Support Ticket Workflow
1. Create ticket: `POST /support-tickets`
2. View ticket: `GET /support-tickets/{ticketId}`
3. Instructor/Admin receives notification: SupportTicketCreated
4. Reply to ticket: `POST /support-tickets/{ticketId}/reply`
5. User receives notification: SupportTicketReply

---

## 📞 Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK |
| 201 | Created |
| 302 | Redirect (after form submission) |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden (access denied) |
| 404 | Not Found |
| 422 | Unprocessable Entity (validation error) |
| 500 | Server Error |

---

## 🔐 Authentication Methods

### Session-Based (Currently Used)
- Login creates session cookie
- Session persists across requests
- Logout destroys session

### Token-Based (Future - Sanctum Ready)
- Bearer token in Authorization header
- Used for API-only requests

---

## 📅 Changelog

### v1.0.0 (2026-06-15)
- ✅ Complete course management system
- ✅ Assignment & quiz support with file uploads
- ✅ Student enrollment filtering
- ✅ Support ticket notifications
- ✅ Profile/password editing for all roles
- ✅ 107/110 tests passing

---

**Last Updated**: 2026-06-15
**API Version**: 1.0
**Framework**: Laravel 11
**Status**: Production Ready
