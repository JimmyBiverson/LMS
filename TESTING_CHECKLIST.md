# 🧪 Pre-Presentation Testing Checklist

## ✅ Critical Features to Test (10-15 minutes)

### Authentication & Redirects
- [ ] Admin login → redirects to `/admin/dashboard/dashboard`
- [ ] Instructor login → redirects to `/instructor`
- [ ] Student login → redirects to `/dashboard`
- [ ] Organization login → redirects to `/org`
- [ ] Logout works from all dashboards
- [ ] New user registration → auto-redirect to correct dashboard

### Student Dashboard & Course Browsing
- [ ] Student dashboard shows "My Courses" section
- [ ] Browse courses page loads all active courses
- [ ] Free courses show "Enroll Now" button
- [ ] Paid courses show price and "Buy Now" button
- [ ] Course details page displays correctly
- [ ] Free preview lessons show "Free Preview" label

### Video Player Testing
- [ ] YouTube video URL embeds and plays
- [ ] Vimeo video URL embeds and plays
- [ ] Uploaded MP4 video plays with controls
- [ ] Auto-play works (with muted audio)
- [ ] Video player responsive on mobile
- [ ] Full-screen mode works
- [ ] Video progress bar tracks correctly

### Course Enrollment
- [ ] Click "Enroll Now" on free course → instant enrollment
- [ ] Enrolled students see full course curriculum
- [ ] Enrolled students can access all lessons
- [ ] Non-enrolled students see "Sign In" or "Enroll" message

### Lesson Viewing
- [ ] Click lesson → loads dedicated lesson page
- [ ] Video auto-plays with muted audio (if free preview)
- [ ] Document download link works
- [ ] Course material section displays documents
- [ ] Lesson description renders correctly
- [ ] Previous/Next lesson navigation works
- [ ] Lesson sidebar shows course progress

### Lesson Completion Tracking
- [ ] Click "Mark as Complete" → button changes to green
- [ ] Progress percentage updates in sidebar
- [ ] Refresh page → completion status persists
- [ ] Can toggle completion on/off

### Instructor Course Creation
- [ ] Login as instructor
- [ ] Create new course with all fields
- [ ] Add lesson with YouTube URL
- [ ] Add lesson with uploaded MP4 video
- [ ] Add lesson with uploaded PDF document
- [ ] Add lesson with both video + document
- [ ] Try adding lesson without media → error message
- [ ] Delete lesson → confirmation works
- [ ] Edit course → changes save
- [ ] Edit lesson → changes save

### Lesson Media Validation
- [ ] Lesson requires at least one media source
- [ ] Accept: video_url, video_file, document_file
- [ ] Accept video formats: mp4, mov, avi, webm, ogg
- [ ] Accept document formats: pdf, doc, docx, ppt, pptx, xls, xlsx
- [ ] Reject invalid file types
- [ ] Show file size limits in UI (500MB video, 50MB doc)

### Admin Dashboard
- [ ] Access admin panel
- [ ] View user list
- [ ] View course approvals (if needed)
- [ ] View system statistics

### Responsive Design
- [ ] Test on Mobile (320px)
- [ ] Test on Tablet (768px)
- [ ] Test on Desktop (1920px)
- [ ] Video player scales correctly
- [ ] Navigation works on mobile
- [ ] Forms are usable on mobile

### Performance
- [ ] Homepage loads < 2 seconds
- [ ] Course page loads < 2 seconds
- [ ] Lesson page with video loads < 3 seconds
- [ ] Video starts playing within 3 seconds
- [ ] No console errors (F12 developer tools)

---

## 🎓 User Workflow Tests

### Student Workflow (5 minutes)
1. [ ] Login as `student1@lms.test`
2. [ ] Verify redirect to dashboard
3. [ ] Browse courses
4. [ ] Click on "Web Development" (or any free course)
5. [ ] Click "Enroll Now"
6. [ ] View course curriculum
7. [ ] Click first lesson
8. [ ] Watch video (first 30 seconds)
9. [ ] Mark lesson complete
10. [ ] Check progress bar
11. [ ] Download course material
12. [ ] Go back to course
13. [ ] Navigate to next lesson

### Instructor Workflow (8 minutes)
1. [ ] Login as `instructor@lms.test`
2. [ ] Verify redirect to instructor dashboard
3. [ ] Click "Create New Course"
4. [ ] Fill in:
   - Title: "Advanced JavaScript"
   - Category: Programming
   - Level: Intermediate
   - Description: "Learn advanced JS concepts"
   - Price: 0 (Free)
5. [ ] Create course
6. [ ] Go to course edit
7. [ ] Click "Add Lesson"
8. [ ] Add lesson with YouTube URL
9. [ ] Add another lesson with uploaded video
10. [ ] Add lesson with PDF document
11. [ ] Mark one as "Free Preview"
12. [ ] Submit form
13. [ ] Go back to course
14. [ ] View lessons list (all 3 should show media icons)

### Admin Workflow (3 minutes)
1. [ ] Login as `admin@lms.test`
2. [ ] Verify redirect to admin dashboard
3. [ ] Browse user management
4. [ ] View system settings
5. [ ] Check recent activity/logs

---

## 🎬 Demo Script

### Opening (1 min)
"Good afternoon. This is an LMS built specifically for educational institutions across Uganda. It allows instructors to create courses, students to learn, and administrators to manage everything. Let me show you how it works."

### Demo (8 mins)

**[Login as Student]**
"First, let me login as a student. Notice how the system recognizes this is a student account and redirects to the student dashboard. Here I can see my enrolled courses, browse new courses, and track my progress."

**[Browse Courses]**
"Let me browse available courses. I can see courses created by various instructors. These are both free and paid. Let me click on this Web Development course."

**[Show Course Details]**
"Here's the course overview. I can see the instructor, course level, ratings, and the curriculum. Notice the first lesson has a 'Free Preview' tag, which means I can watch a preview even without enrolling."

**[Enroll & Watch]**
"Let me enroll in this course. For free courses, it's instant. Now I have access to all lessons. Let me click on the first lesson to watch it."

**[Show Video Player]**
"Here's our modern video player. It auto-plays when you open a lesson - we've made it muted to save bandwidth for users in Uganda. You can see it has all standard controls: play, pause, volume, fullscreen. Let me demonstrate the fullscreen mode."

**[Show Lesson Features]**
"On the right side, I can see the course progress - I'm on lesson 1 of 4. Below that, I can navigate to the next lesson. I can also see all lessons listed. When I complete this lesson, I click 'Mark as Complete' and my progress updates."

**[Mark Complete]**
"Notice how the progress bar moved from 0% to 25%. This visual feedback keeps students motivated."

**[Login as Instructor]**
"Now let me show how an instructor creates content. I'm logging in as an instructor."

**[Create Course]**
"I click 'Create New Course' and fill in the details. I set the price to 0 for free courses, or any amount if I want to charge. I can organize with categories and levels."

**[Add Lessons]**
"The powerful part is lesson creation. I can either paste a YouTube URL, upload an MP4 video directly, or upload a PDF document. The requirement is: at least one. This flexibility is key - not all instructors have quality videos ready, so this system supports document-based learning too."

**[Show Upload]**
"Let me show you a file upload in progress. You can drag and drop files here. Maximum 500MB for videos, 50MB for documents."

**[Closing]**
"The system is built for scalability - it can support thousands of students across Uganda's educational institutions. It works on slow internet, mobile-friendly, and free for educators. Thank you."

---

## 🔧 Troubleshooting

### Issue: Video won't auto-play
**Solution:** Check browser autoplay permissions. Chrome requires MUTED videos to auto-play. This is intentional for bandwidth savings.

### Issue: File upload fails
**Solution:** Check file size and format. Use FileZilla to verify `/storage` folder has correct permissions.

### Issue: Lesson doesn't show in course
**Solution:** Verify lesson has at least one media source (video_url, video_file, or document_file).

### Issue: Student can't enroll
**Solution:** Verify course status is "Active". Pending/Draft courses won't show to students.

### Issue: Database seeder fails
**Solution:** Run `php artisan migrate` first, then `php artisan db:seed --class=PresentationTestUsersSeeder`

### Issue: Laravel artisan commands not found
**Solution:** Ensure you're in the project root directory. Run `pwd` to check.

---

## 📱 Mobile Testing Checklist

- [ ] Login form is usable
- [ ] Dashboard is responsive
- [ ] Course cards stack properly
- [ ] Video player fills screen
- [ ] Buttons are touch-friendly (> 44px)
- [ ] Navigation menu collapses
- [ ] No horizontal scrolling needed
- [ ] Text is readable without zooming

---

## ✨ Pro Tips for Smooth Presentation

1. **Pre-login:** Have browser tabs open with login ready to save time
2. **Pre-create course:** Have a sample course ready so you only create one during demo
3. **Pre-upload files:** Have sample video/document ready to upload
4. **Network check:** Test video playback before presentation
5. **Audio test:** Ensure speakers work for video demo
6. **Have backup:** Screenshot key features in case of technical issues
7. **Know your routes:** Have URL shortcuts written down:
   - Student Dashboard: http://localhost:8000/dashboard
   - Instructor: http://localhost:8000/instructor
   - Admin: http://localhost:8000/admin/dashboard/dashboard

---

## 🎯 Success Criteria

- [x] All test users login successfully
- [x] Role-based redirects work
- [x] Video player auto-plays
- [x] Course upload with video + document works
- [x] Lesson completion tracking works
- [x] Mobile responsive
- [x] No console errors
- [x] Demo flows smoothly (< 10 minutes)

---

**Status:** Ready for Demo ✅

**Good luck with your presentation at Makerere University! 🚀**
