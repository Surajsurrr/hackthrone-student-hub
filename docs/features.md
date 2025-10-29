# All-in-One Student Hub – Feature Plan (October 2025)

This document maps your comprehensive feature list to the current codebase, proposes an MVP slice, and lays out a practical roadmap to reach the full vision.

## Current coverage (what’s already in the repo)

- Auth and roles: Students, Colleges, Companies, Admin (`login.php`, `register.php`, role dashboards)
- Student basics: Profile (basic fields), Notes upload/fetch (`api/student/*`), AI Coach chat (`api/student/getAIResponse.php`, `student/ai_coach.php`), Dashboard
- Opportunities: Hackathons (College) and Internships (Company) posting + student views
- Admin: Manage users/events
- UI: Student dashboard modernized (Overview, Profile, Opportunities, Notes, AI Coach, Achievements, Calendar)

## Gaps vs. full feature list (high level)

- Smart Profile: Interests, targets, endorsements, resume builder, certifications, profile completeness, custom URL, portfolio, video intro, external sync (LinkedIn/GitHub)
- Notes Hub (AI-powered): Categorization, summarization, flashcards, adaptive quizzes, ratings/reviews, search, versioning, annotations, sharing permissions, offline, converters, LaTeX, code highlighting, dark mode
- Exam Prep Zone: Syllabus tracker, PYPs, playlists, schedules, analytics, mocks (public/private/AI), in-depth test engine features
- Mentorship: Verified mentors, scheduling, payments, ratings, video/whiteboard/chat, artifacts
- Community & Circles: Forums, real-time chat/voice/video, resources, events, gamification, moderation
- Gamification: XP/coins, levels, streaks, badges, leaderboards, challenges, shop
- Collaboration & Projects: Teams, kanban, sprints, files, wiki, reviews, showcase
- Wellness: SOS, guided exercises, trackers, journaling
- Advanced AI: Study coach, curation, predictive analytics, auto plans
- Extended ecosystem: Parent dashboard, alumni network, placement cell suite, library
- Productivity: Extension, email parsing, calendar suite, scanner
- Accessibility/Regionalization: Multi-language, accessibility modes, offline-first/PWA

## MVP v1 (4–6 weeks) – deliver real, daily value fast

- Smart Profile (Phase 1)
  - Fields: interests/tags, exam targets, branch, bio
  - Skills + endorsements model (peer endorsements only)
  - Profile score heuristic + suggestions (server-side)
- Notes Hub (Phase 1)
  - Ratings + reviews; subject tag; basic search (title/subject)
- Opportunities (Phase 1)
  - Application tracker (applied/in-process/selected/rejected) for jobs/internships/hackathons
  - Reminders for deadlines
- Gamification (Phase 1)
  - XP + coins counters; a few badges; activity hooks (upload note, apply opportunity, complete profile)
- Wellness (Phase 1)
  - Floating SOS button with quick relief modal
- Dashboard (Phase 1)
  - Widgets for applications, reminders, XP/coins, profile score

Nice-to-have during MVP if time permits: Calendar widget polish, simple achievements feed, email templates for follow-ups.

## v1.1 (6–10 weeks)
- Notes Hub: AI summarization, flashcards (spaced repetition), improved search; share-to-circle
- Smart Profile: Resume/CV export, certifications gallery, recommendation letters
- Gamification: Streaks, leaderboards, shop (non-monetary)
- Mentorship: Read-only catalog + booking requests (no payments yet)
- Accessibility: Dark mode, improved contrast, keyboard nav

## v1.2 (10–16 weeks)
- Exam Zone: Syllabus tracker, PYPs, public mocks
- Mentorship: Payments and session tooling baseline (video, whiteboard)
- Community: Study circles (forums + resource library)
- AI Study Coach: Personalized suggestions on dashboard

## Data model – MVP additions

See `database/migrations/2025_10_28_core_extensions.sql` for schema. Highlights:

- skills, user_skills, endorsements
- achievements, user_achievements, user_stats (xp, coins, profile_score)
- applications (track student applications across entities)
- notes_ratings (ratings/reviews)
- reminders (personal reminders)
- student_profiles: extra columns (branch, bio, interests)

## API surface (MVP – suggested endpoints)

- Student
  - GET `/api/student/getSkills.php`, POST `/api/student/addSkill.php`, POST `/api/student/endorseSkill.php`
  - GET `/api/student/getApplications.php`, POST `/api/student/addApplication.php`, POST `/api/student/updateApplication.php`
  - POST `/api/student/addReminder.php`, GET `/api/student/getReminders.php`
  - POST `/api/student/rateNote.php`
  - GET `/api/student/getStats.php` (xp, coins, profile score)
- Internal hooks (server): awardXP($userId, $action), grantBadge($userId, $badgeCode)

## UX notes

- Keep the new modern dashboard; add small widgets for Applications, Reminders, XP/Coins.
- Floating SOS button visible site-wide (student scope initially).
- Use progressive enhancement: graceful fallback when AI endpoints are not configured.

## Delivery mechanics

- Database migrations in `database/migrations/`
- Feature flags via simple constants in `includes/config.php` where feasible
- API endpoints return `{ success, message?, data }`
- Non-breaking changes: additive schema, no destructive ops without backups

## Risks

- Scope creep; keep MVP tight
- Payment integrations and video infra require careful planning
- AI features rely on model endpoints and content moderation policies

## Next actions

1) Apply the migration on a dev DB
2) Implement endpoints for Applications + Notes rating first
3) Wire dashboard widgets to these endpoints
4) Add XP hooks on these actions
