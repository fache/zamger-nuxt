<?php

// List of services with wiring code

$wiring = array(
	array(
		"path" => "/", 
		"description" => "Top level endpoint", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"person" => array("href" => "person"),
			"course" => array("href" => "course"),
			"group" => array("href" => "group"),
			"class" => array("href" => "class"),
			"exam" => array("href" => "exam"),
			"homework" => array("href" => "homework"),
			"quiz" => array("href" => "quiz"),
			"event" => array("href" => "event"),
			"certificate" => array("href" => "certificate"),
		) // TODO: define identity links for classes
	),


	// INBOX
	
	array(
		"path" => "inbox", 
		"description" => "Recent messages", 
		"method" => "GET",
		"params" => array( "messages" => "int", "start" => "int" ),
		"code" => "if (\$messages == 0) \$messages=5; \$msgs = Message::latest(\$messages, \$start); return \$msgs;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"message" => array("href" => "inbox/{id}"),
			"unread" => array("href" => "inbox/unread"),
		) // TODO: define identity links for classes
	),
	array(
		"path" => "inbox/outbox", 
		"description" => "Sent messages", 
		"method" => "GET",
		"params" => array( "messages" => "int", "start" => "int" ),
		"code" => "if (\$messages == 0) \$messages=5; \$msgs = Message::outbox(\$messages, \$start); return \$msgs;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"message" => array("href" => "inbox/{id}"),
			"count" => array("href" => "inbox/count"),
			"unread" => array("href" => "inbox/unread"),
		) // TODO: define identity links for classes
	),
	array(
		"path" => "inbox/count", 
		"description" => "Total personal messages", 
		"method" => "GET",
		"code" => "return Message::count();", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"inbox" => array("href" => "inbox"),
			"message" => array("href" => "inbox/{id}"),
			"unread" => array("href" => "inbox/unread"),
		) // TODO: define identity links for classes
	),
	array(
		"path" => "inbox/{id}", 
		"description" => "Get message", 
		"method" => "GET", 
		"code" => "\$msg = Message::fromId(\$id); return \$msg;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"inbox" => array("href" => "inbox"),
			"unread" => array("href" => "inbox/unread"),
		) // TODO: define identity links for classes
	),
	array(
		"path" => "inbox/unread", 
		"description" => "Unread messages", 
		"method" => "GET", 
		"code" => "\$msgs = Message::unread(); return \$msgs;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"inbox" => array("href" => "inbox"),
			"message" => array("href" => "inbox/{id}"),
		) // TODO: define identity links for classes
	),


	// PERSON
	
	array(
		"path" => "person", 
		"description" => "Current user", 
		"method" => "GET", 
		"code" => "\$p = Person::fromId(Session::\$userid); \$p->getTitles(); return \$p;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"self" => array("href" => "person/[id]"),
			"personById" => array("href" => "person/{id}"),
			"personByLogin" => array("href" => "person/byLogin?login={login}"),
			"search" => array("href" => "person/search?query={query}")
		) // TODO: define identity links for classes
	),
	array(
		"path" => "person/{id}", 
		"description" => "Find user by id", 
		"method" => "GET", 
		"code" => "\$p = Person::fromId(\$id); \$p->getTitles(); return \$p;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"self" => array("href" => "person/[id]"),
			"currentUser" => array("href" => "person"),
			"personByLogin" => array("href" => "person/byLogin?login={login}"),
			"search" => array("href" => "person/search?query={query}")
		)
	),
	array(
		"path" => "person/byLogin", 
		"description" => "Find user by login", 
		"method" => "GET", 
		"params" => array( "login" => "string" ),
		"code" => "\$p = Person::fromLogin(\$login); \$p->getTitles(); return \$p;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"self" => array("href" => "person/[id]"),
			"currentUser" => array("href" => "person"),
			"personById" => array("href" => "person/{id}"),
			"search" => array("href" => "person/search?query={query}")
		)
	),
	array(
		"path" => "person/search", 
		"description" => "Search users", 
		"search" => "personById",
		"method" => "GET", 
		"params" => array( "query" => "string" ),
		"code" => "return Person::search(\$query);", 
		"autoresolve" => array(),
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"currentUser" => array("href" => "person"),
			"personByLogin" => array("href" => "person/byLogin?login={login}"),
			"personById" => array("href" => "person/{id}")
		)
	),
	
	array(
		"path" => "extendedPerson/{id}", 
		"description" => "Extended details on person (student)", 
		"method" => "GET", 
		"code" => "\$p = ExtendedPerson::fromId(\$id); return \$p;", 
		"autoresolve" => array(),
		"acl" => "self(\$id) || privilege('studentska')"
	),
	
	
	
	// COURSE
	
	array(
		"path" => "course", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "all()",
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),

	
	array(
		"path" => "course/{id}", 
		"description" => "Course", 
		"method" => "GET", 
		"code" => "\$cy = AcademicYear::getCurrent(); return CourseUnitYear::fromCourseAndYear(\$id, \$cy->id);", 
		"acl" => "all()",
		"autoresolve" => array("CourseUnit", "AcademicYear", "Institution", "Scoring", "Programme", "ProgrammeType"),
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),
	
	array(
		"path" => "course/{id}/{year}", 
		"description" => "Course", 
		"method" => "GET", 
		"code" => "return CourseUnitYear::fromCourseAndYear(\$id, \$year);", 
		"acl" => "all()",
		"autoresolve" => array("CourseUnit", "AcademicYear", "Institution", "Scoring", "Programme"),
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),
	
	array(
		"path" => "course/programme/{programme}/{semester}", 
		"description" => "List of courses on programme and semester", 
		"method" => "GET", 
		"code" => "return CourseOffering::getCoursesOffered(AcademicYear::getCurrent()->id, \$programme, \$semester);", 
		"acl" => "all()",
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),

	array(
		"path" => "course/teacher/{teacher}", 
		"description" => "List of courses for teacher", 
		"method" => "GET", 
		"code" => "return CourseUnitYear::forTeacher(\$teacher);", 
		"acl" => "self(\$teacher) || privilege('studentska')",
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),
	
	array(
		"path" => "course/student/{student}", 
		"description" => "List current courses for student", 
		"method" => "GET", 
		"code" => "return Portfolio::getCurrentForStudent(\$student);", 
		"acl" => "self(\$student) || privilege('studentska')",
		"autoresolve" => array("CourseOffering", "AcademicYear", "CourseUnit", "Programme"),
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),
	
	array(
		"path" => "course/{course}/student/{student}", 
		"description" => "Details of specific course for student", 
		"method" => "GET", 
		"params" => array( "year" => "int" ),
		"code" => "\$p = Portfolio::fromCourseUnit(\$student, \$course, \$year); \$p->getScore(); \$p->getGrade(); return \$p;", 
		"acl" => "self(\$student) || privilege('studentska') || teacherLevel(\$course, \$year)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"course" => array("href" => "course/{course}/{year}"),
			'coursesOnProgramme' => array('href' => 'course/programme/{programme}/{semester}'),
			'coursesForStudent' => array('href' => 'course/student/{student}'),
			'coursesForTeacher' => array('href' => 'course/teacher/{teacher}')
		)
	),
	
	
	
	// GROUP
	
	array(
		"path" => "group", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/{id}", 
		"description" => "Get group with id", 
		"method" => "GET", 
		"params" => array( "details" => "bool" ),
		"code" => "return Group::fromId(\$id, \$details);", 
		"acl" => "teacherLevelGroup(\$id)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/{id}/student/{student}", 
		"description" => "Check if student is enrolled in group", 
		"method" => "GET", 
		"code" => "\$grp = Group::fromId(\$id, \$details); return \$grp->isMember(\$student);", 
		"acl" => "teacherLevelGroup(\$id)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/{id}/student/{student}", 
		"description" => "Enroll student in group (removing from other groups)", 
		"method" => "PUT", 
		"code" => "\$grp = Group::fromId(\$id, \$details); return \$grp->addMember(\$student, true);", 
		"acl" => "teacherLevelGroup(\$id)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/{id}/student/{student}", 
		"description" => "Enroll student in group (not removing from other groups)", 
		"method" => "POST", 
		"code" => "\$grp = Group::fromId(\$id, \$details); return \$grp->addMember(\$student, false);", 
		"acl" => "teacherLevelGroup(\$id)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/{id}/student/{student}", 
		"description" => "Disenroll student from group", 
		"method" => "DELETE", 
		"code" => "\$grp = Group::fromId(\$id, \$details); return \$grp->removeMember(\$student);", 
		"acl" => "teacherLevelGroup(\$id)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/course/{course}", 
		"description" => "Get list of student groups on course", 
		"method" => "GET", 
		"params" => array( "year" => "int", "includeVirtual" => "bool", "getMembers" => "bool" ),
		"code" => "return Group::forCourseAndYear(\$course, \$year, \$includeVirtual);", 
		"acl" => "teacherLevel(\$course, \$year)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/course/{course}/allStudents", 
		"description" => "Get all students on course (virtual group)", 
		"method" => "GET", 
		"params" => array( "year" => "int" ),
		"code" => "return Group::virtualForCourse(\$course, \$year);", 
		"acl" => "teacherLevel(\$course, \$year)", // FIXME use teacherLevelGroup and id of virtual group
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	array(
		"path" => "group/course/{course}/student/{student}", 
		"description" => "Get the list of groups that a student belongs to for given course", 
		"method" => "GET", 
		"params" => array( "year" => "int" ),
		"code" => "if (\$student == 0) \$student=Session::\$userid; return Group::fromStudentAndCourse(\$student, \$course, \$year);",
		"acl" => "self(\$student) || teacherLevel(\$course, \$year)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"group" => array("href" => "group/{id}"),
			"allGroups" => array("href" => "group/course/{course}/?year={year}"),
			"allStudents" => array("href" => "group/course/{course}/allStudents/?year={year}"),
			"forStudent" => array("href" => "group/course/{course}/student/{student}/?year={year}"),
		)
	),
	
	
	
	// COMMENT
	
	array(
		"path" => "comment", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"comment" => array("href" => "comment/{id}"),
			"allCommentsForStudent" => array("href" => "comment/group/{group}/student/{student}"),
		)
	),
	
	array(
		"path" => "comment/{id}", 
		"description" => "Get comment with specific ID", 
		"method" => "GET", 
		"code" => "return Comment::fromId(\$id);", 
		"acl" => "teacherLevelGroup(Comment::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"comment" => array("href" => "comment/{id}"),
			"allCommentsForStudent" => array("href" => "comment/group/{group}/student/{student}"),
		)
	),
	
	array(
		"path" => "comment/{id}", 
		"description" => "Delete comment", 
		"method" => "DELETE", 
		"code" => "\$com = Comment::fromId(\$id); \$com->delete();", 
		"acl" => "teacherLevelGroup(Comment::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"comment" => array("href" => "comment/{id}"),
			"allCommentsForStudent" => array("href" => "comment/group/{group}/student/{student}"),
		)
	),
	
	array(
		"path" => "comment/{id}", 
		"description" => "Update comment", 
		"method" => "PUT", 
		"params" => array( "comment" => "object" ),
		"classes" => array( "comment" => "Comment" ),
		"code" => "\$comment->teacher->id = Session::\$userid; \$comment->validate(); \$comment->update();", 
		"acl" => "teacherLevelGroup(Comment::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"comment" => array("href" => "comment/{id}"),
			"allCommentsForStudent" => array("href" => "comment/group/{group}/student/{student}"),
		)
	),
	
	array(
		"path" => "comment/group/{group}/student/{student}", 
		"description" => "Get all comments on student activity in group", 
		"method" => "GET", 
		"code" => "return Comment::forStudentInGroup(\$student, \$group);", 
		"acl" => "teacherLevelGroup(\$group)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"comment" => array("href" => "comment/{id}"),
			"allCommentsForStudent" => array("href" => "comment/group/{group}/student/{student}"),
		)
	),
	
	array(
		"path" => "comment/group/{group}/student/{student}", 
		"description" => "Add new comment on student activity in group", 
		"method" => "POST", 
		"params" => array( "comment" => "object" ),
		"classes" => array( "comment" => "Comment" ),
		"code" => "\$comment->teacher->id = Session::\$userid; \$comment->validate(); \$comment->add()", 
		"acl" => "teacherLevelGroup(\$group)",
		"autoresolve" => array(),
		"hateoas_links" => array(
			"comment" => array("href" => "comment/{id}"),
			"allCommentsForStudent" => array("href" => "comment/group/{group}/student/{student}"),
		)
	),
	
	
	// CLASS/ATTENDANCE
	
	array(
		"path" => "class", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	array(
		"path" => "class/{id}", 
		"description" => "Get class information", 
		"method" => "GET", 
		"code" => "return ZClass::fromId(\$id);", 
		"acl" => "teacherLevelGroup(ZClass::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	array(
		"path" => "class/group/{group}", 
		"description" => "List of classes registered for group", 
		"method" => "GET", 
		"code" => "return ZClass::fromGroup(\$group);", 
		"acl" => "teacherLevelGroup(\$group)",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	array(
		"path" => "class/{id}/student/{student}", 
		"description" => "Get information on attendance of student", 
		"method" => "GET", 
		"code" => "\$att = Attendance::fromStudentAndClass(\$student, \$id); \$att->getPresence(); return \$att;", 
		"acl" => "teacherLevelGroup(ZClass::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	array(
		"path" => "class/{id}/student/{student}", 
		"description" => "Update attendance of student", 
		"method" => "POST", 
		"params" => array( "att" => "object" ),
		"classes" => array( "att" => "Attendance" ),
		"code" => "\$att->student->id = \$student; \$att->ZClass->id = \$id; \$att->setPresence(\$att->presence); return \$att;", 
		"acl" => "teacherLevelGroup(ZClass::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	array(
		"path" => "class/{id}/student/{student}", 
		"description" => "Set attendance of student", 
		"method" => "PUT", 
		"params" => array( "att" => "object" ),
		"classes" => array( "att" => "Attendance" ),
		"code" => "\$att->student->id = \$student; \$att->ZClass->id = \$id; \$att->setPresence(\$att->presence); return \$att;", 
		"acl" => "teacherLevelGroup(ZClass::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	array(
		"path" => "class/{id}/student/{student}", 
		"description" => "Delete attendance of student (set it to neutral value)", 
		"method" => "DELETE", 
		"code" => "\$att = Attendance::fromStudentAndClass(\$student, \$id); \$att->deletePresence(); return \$att;", 
		"acl" => "teacherLevelGroup(ZClass::fromId(\$id)->Group->id)",
		"hateoas_links" => array(
			"class" => array("href" => "class/{id}"),
			"allClassesInGroup" => array("href" => "class/group/{group}"),
			"attendance" => array("href" => "class/{id}/student/{student}"),
		)
	),
	
	
	
	// EXAM
	
	array(
		"path" => "exam", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/{id}", 
		"description" => "Information about exam", 
		"method" => "GET", 
		"params" => array( "with_results" => "bool" ),
		"code" => "\$exam = Exam::fromId(\$id); if (\$with_results) \$exam->results = ExamResult::fromExam(\$id); return \$exam;", 
		"acl" => "teacherLevel(Exam::fromId(\$id)->CourseUnit->id, Exam::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/course/{course}", 
		"description" => "List of exams on course", 
		"method" => "GET", 
		"code" => "return Exam::fromCourseAndYear(\$course);", 
		"acl" => "teacherLevel(\$course, 0)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/course/{course}/{year}", 
		"description" => "Information about exam", 
		"method" => "GET", 
		"code" => "return Exam::fromCourseAndYear(\$course, \$year);", 
		"acl" => "teacherLevel(\$course, \$year)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/{id}/student/{student}", 
		"description" => "Information about exam result achieved by student", 
		"method" => "GET", 
		"code" => "return ExamResult::fromStudentAndExam(\$student, \$id);", 
		"acl" => "teacherLevel(Exam::fromId(\$id)->CourseUnit->id, Exam::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/{id}/student/{student}", 
		"description" => "Update exam result for student", 
		"method" => "PUT", 
		"params" => array( "examresult" => "object" ),
		"code" => "\$er = ExamResult::fromStudentAndExam(\$student, \$id); \$er->setExamResult(\$examresult->result); return \$er;", 
		"acl" => "teacherLevel(Exam::fromId(\$id)->CourseUnit->id, Exam::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/{id}/student/{student}", 
		"description" => "Add new exam result for student", 
		"method" => "POST", 
		"params" => array( "examresult" => "object" ),
		"code" => "\$er = ExamResult::fromStudentAndExam(\$student, \$id); \$er->setExamResult(\$examresult->result); return \$er;", 
		"acl" => "teacherLevel(Exam::fromId(\$id)->CourseUnit->id, Exam::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/{id}/student/{student}", 
		"description" => "Delete exam result for student", 
		"method" => "DELETE", 
		"params" => array( "examresult" => "object" ),
		"code" => "\$er = ExamResult::fromStudentAndExam(\$student, \$id); \$er->deleteExamResult(); return \$er;", 
		"acl" => "teacherLevel(Exam::fromId(\$id)->CourseUnit->id, Exam::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	array(
		"path" => "exam/latest/{student}", 
		"description" => "Latest exam results for student", 
		"method" => "GET", 
		"code" => "return ExamResult::getLatestForStudent(Session::\$userid, 10);", 
		"acl" => "self(\$student)",
		"hateoas_links" => array(
			"exam" => array("href" => "exam/{id}"),
			"allExamsForCourse" => array("href" => "exam/course/{course}"),
			"examResult" => array("href" => "exam/{id}/student/{student}"),
			"latestExamResults" => array("href" => "exam/latest"),
		)
	),
	
	
	
	// HOMEWORK
	
	array(
		"path" => "homework", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
		)
	),
	
	array(
		"path" => "homework/{id}", 
		"description" => "Information about homework", 
		"method" => "GET", 
		"code" => "return Homework::fromId(\$id);", 
		"acl" => "teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
		)
	),
	
	array(
		"path" => "homework/course/{course}", 
		"description" => "List of homeworks on course", 
		"method" => "GET", 
		"code" => "return Homework::fromCourse(\$course, AcademicYear::getCurrent()->id);", 
		"acl" => "teacherLevel(\$course, 0)",
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
		)
	),
	
	array(
		"path" => "homework/course/{course}/{year}", 
		"description" => "List of homeworks on course", 
		"method" => "GET", 
		"code" => "return Homework::fromCourse(\$course, \$year);", 
		"acl" => "teacherLevel(\$course, \$year)",
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
		)
	),
	
	array(
		"path" => "homework/{id}/{asgn}/student/{student}", 
		"description" => "Status of submitted homework for student (with assignment number)", 
		"method" => "GET", 
		"code" => "return Assignment::fromStudentHomeworkNumber(\$student, \$id, \$asgn);", 
		"acl" => "self(\$student) || teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)", // if student is not on course, there will be no assignment
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
			"homeworkFile" => array("href" => "homework/{id}/{asgn}/student/{student}/file"),
		)
	),
	
	array(
		"path" => "homework/{id}/{asgn}/student/{student}", 
		"description" => "Submit homework (for students)", 
		"method" => "POST", 
		"params" => array( "homework" => "file" ),
		"code" => "\$asgn = Assignment::fromStudentHomeworkNumber(\$student, \$id, \$asgn); \$asgn->submit(\$homework, Session::\$userid); return \$asgn;", 
		"acl" => "isStudent(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id) && AccessControl::self(\$student) || teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)", // Teacher can submit homework on behalf of student
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
		)
	),
	
	array(
		"path" => "homework/{id}/{asgn}/student/{student}", 
		"description" => "Change homework status (for teachers)", 
		"method" => "PUT", 
		"params" => array( "assign" => "object" ),
		"classes" => array( "assign" => "Assignment" ),
		"code" => "\$assign->author->id = Session::\$userid; \$assign->add(); return \$assign;", 
		"acl" => "teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"homework" => array("href" => "homework/{id}"),
			"allHomeworksForCourse" => array("href" => "homework/course/{course}/{year}"),
			"homeworkAssignment" => array("href" => "homework/{id}/{asgn}/student/{student}"),
		)
	),
	
	array(
		"path" => "homework/{id}/{asgn}/student/{student}/file", 
		"description" => "Get homework file for given assignment", 
		"method" => "GET",
		"encoding" => "none", // Skip JSON encoding result
		"code" => "\$asgn = Assignment::fromStudentHomeworkNumber(\$student, \$id, \$asgn); \$file = \$asgn->getFile(); return file_get_contents(\$file->fullPath());", 
		"acl" => "self(\$student) || teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)", // if student is not on course, there will be no assignment
	),
	
	array(
		"path" => "homework/{id}/{asgn}/getAll", 
		"description" => "Get homework files of all students for given assignment", 
		"method" => "GET",
		"params" => array( "filenames" => "string" ),
		"encoding" => "none", // Skip JSON encoding result
		"code" => "header('Content-Type: application/zip'); \$zip = Assignment::getAllAssignments(\$id, \$asgn, \$filenames); readfile(\$zip->fullPath()); return '';", 
		"acl" => "teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)",
	),
	
	array(
		"path" => "homework/{id}/{asgn}/autotest", 
		"description" => "Get .autotest file for assignment", 
		"method" => "GET",
		"code" => "return AutotestFile::fromHomeworkNumber(\$id, \$asgn);", 
		"acl" => "teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)",
	),
	
	array(
		"path" => "homework/{id}/{asgn}/autotest", 
		"description" => "Update .autotest file for assignment", 
		"method" => "PUT",
		"params" => array( "autotest" => "object" ),
		"classes" => array( "autotest" => "AutotestFile" ),
		"code" => "\$autotest->update(\$id, \$asgn); return \$autotest;", 
		"acl" => "teacherLevel(Homework::fromId(\$id)->CourseUnit->id, Homework::fromId(\$id)->AcademicYear->id)",
	),
	
	
	
	// HOMEWORK
	
	array(
		"path" => "quiz", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/{id}", 
		"description" => "Information about quiz", 
		"method" => "GET", 
		"code" => "return Quiz::fromId(\$id);", 
		"acl" => "teacherLevel(Quiz::fromId(\$id)->CourseUnit->id, Quiz::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/{id}/take", 
		"description" => "Get quiz questions with offered answers", 
		"method" => "GET", 
		"code" => "return Quiz::take(Session::\$userid, \$id);", 
		"acl" => "isStudent(Quiz::fromId(\$id)->CourseUnit->id, Quiz::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizSubmit" => array("href" => "quiz/{id}/submit"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/{id}/submit", 
		"description" => "When student takes a quiz and completes all answers, they submit the Quiz object here", 
		"method" => "POST", 
		"params" => array( "quiz" => "object" ),
		"classes" => array( "quiz" => "Quiz" ),
		"code" => "return \$quiz->submit(Session::\$userid);", 
		"acl" => "isStudent(\$quiz->CourseUnit->id, \$quiz->AcademicYear->id)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/{id}/student", 
		"description" => "Quiz results for student", 
		"method" => "GET", 
		"code" => "return QuizResult::fromStudentAndQuiz(Session::\$userid, \$id);", 
		"acl" => "isStudent(Quiz::fromId(\$id)->CourseUnit->id, Quiz::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/{id}/student/{student}", 
		"description" => "Quiz results for student", 
		"method" => "GET", 
		"code" => "return QuizResult::fromStudentAndQuiz(\$student, \$id);", 
		"acl" => "teacherLevel(Quiz::fromId(\$id)->CourseUnit->id, Quiz::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/{id}/student/{student}", 
		"description" => "Delete result (reset quiz) for student", 
		"method" => "DELETE", 
		"code" => "\$qr = QuizResult::fromStudentAndQuiz(\$student, \$id); \$qr->delete();", 
		"acl" => "teacherLevel(Quiz::fromId(\$id)->CourseUnit->id, Quiz::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/course/{course}", 
		"description" => "List of quizzes for course", 
		"method" => "GET", 
		"code" => "return Quiz::fromCourse(\$course);", 
		"acl" => "teacherLevel(\$course, 0)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/course/{course}/{year}", 
		"description" => "List of quizzes for course", 
		"method" => "GET", 
		"code" => "return Quiz::fromCourse(\$course, \$year);", 
		"acl" => "teacherLevel(\$course, \$year)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	array(
		"path" => "quiz/latest/{student}", 
		"description" => "Currently open quizzes for student", 
		"method" => "GET", 
		"code" => "return Quiz::getLatestForStudent(\$student);", 
		"acl" => "self(\$student)",
		"hateoas_links" => array(
			"quiz" => array("href" => "quiz/{id}"),
			"quizTake" => array("href" => "quiz/{id}/take"),
			"quizResults" => array("href" => "quiz/{id}/student"),
			"quizResultsStudent" => array("href" => "quiz/{id}/student/{student}"),
			"allQuizzesForCourse" => array("href" => "quiz/course/{course}/{year}"),
			"latestQuizzesForStudent" => array("href" => "quiz/latest/{student}"),
		)
	),
	
	
	
	// EVENT
	
	array(
		"path" => "event", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"event" => array("href" => "event/{id}"),
			"eventRegister" => array("href" => "event/{id}/register/{student}"),
			"eventsUpcoming" => array("href" => "event/upcoming/{student}"),
			"eventsRegistered" => array("href" => "event/registered/{student}"),
		)
	),
	
	array(
		"path" => "event/{id}", 
		"description" => "Information about event", 
		"method" => "GET", 
		"code" => "return Event::fromId(\$id);", 
		"acl" => "teacherLevel(Event::fromId(\$id)->CourseUnit->id, Event::fromId(\$id)->AcademicYear->id) || isStudent(Event::fromId(\$id)->CourseUnit->id, Event::fromId(\$id)->AcademicYear->id)",
		"hateoas_links" => array(
			"event" => array("href" => "event/{id}"),
			"eventRegister" => array("href" => "event/{id}/register/{student}"),
			"eventsUpcoming" => array("href" => "event/upcoming/{student}"),
			"eventsRegistered" => array("href" => "event/registered/{student}"),
		)
	),
	
	array(
		"path" => "event/{id}/register/{student}", 
		"description" => "Register for event", 
		"method" => "POST", 
		"code" => "\$evt = Event::fromId(\$id); if (\$student == Session::\$userid) \$evt->register(\$student); else /* teacher */ \$evt->register(\$student, true, false); return \$evt;", 
		"acl" => "teacherLevel(Event::fromId(\$id)->CourseUnit->id, Event::fromId(\$id)->AcademicYear->id) || self(\$student)",
		"hateoas_links" => array(
			"event" => array("href" => "event/{id}"),
			"eventRegister" => array("href" => "event/{id}/register/{student}"),
			"eventsUpcoming" => array("href" => "event/upcoming/{student}"),
			"eventsRegistered" => array("href" => "event/registered/{student}"),
		)
	),
	
	array(
		"path" => "event/{id}/register/{student}", 
		"description" => "Unregister from event", 
		"method" => "DELETE", 
		"code" => "\$evt = Event::fromId(\$id); \$evt->unregister(\$student); return \$evt;", 
		"acl" => "teacherLevel(Event::fromId(\$id)->CourseUnit->id, Event::fromId(\$id)->AcademicYear->id) || self(\$student)",
		"hateoas_links" => array(
			"event" => array("href" => "event/{id}"),
			"eventRegister" => array("href" => "event/{id}/register/{student}"),
			"eventsUpcoming" => array("href" => "event/upcoming/{student}"),
			"eventsRegistered" => array("href" => "event/registered/{student}"),
		)
	),
	
	array(
		"path" => "event/upcoming/{student}", 
		"description" => "List upcoming events for student", 
		"method" => "GET", 
		"code" => "return Event::upcomingForStudent(\$student);", 
		"acl" => "self(\$student)",
		"hateoas_links" => array(
			"event" => array("href" => "event/{id}"),
			"eventRegister" => array("href" => "event/{id}/register/{student}"),
			"eventsUpcoming" => array("href" => "event/upcoming/{student}"),
			"eventsRegistered" => array("href" => "event/registered/{student}"),
		)
	),
	
	array(
		"path" => "event/registered/{student}", 
		"description" => "List events that student is already registered for", 
		"method" => "GET", 
		"code" => "return Event::registeredForStudent(\$student);", 
		"acl" => "self(\$student)",
		"hateoas_links" => array(
			"event" => array("href" => "event/{id}"),
			"eventRegister" => array("href" => "event/{id}/register/{student}"),
			"eventsUpcoming" => array("href" => "event/upcoming/{student}"),
			"eventsRegistered" => array("href" => "event/registered/{student}"),
		)
	),
	
	
	
	// CERTIFICATE
	
	array(
		"path" => "certificate", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),
	
	array(
		"path" => "certificate/{id}", 
		"description" => "Information about particular certificate request", 
		"method" => "GET", 
		"code" => "return Certificate::fromId(\$id);", 
		"acl" => "self(Certificate::fromId(\$id)->student->id) || privilege('studentska')",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),
	
	array(
		"path" => "certificate/{id}", 
		"description" => "Update certificate status", 
		"method" => "PUT", 
		"params" => array( "certificate" => "object" ),
		"classes" => array( "certificate" => "Certificate" ),
		"code" => "\$result = new stdClass; \$result->success = \$certificate->setStatus(\$certificate->status); return \$result;", 
		"acl" => "privilege('studentska')",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),
	
	array(
		"path" => "certificate/{id}", 
		"description" => "Cancel certificate request", 
		"method" => "DELETE", 
		"code" => "return Certificate::fromId(\$id)->cancel();", 
		"acl" => "self(Certificate::fromId(\$id)->student->id) || privilege('studentska')",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),
	
	array(
		"path" => "certificate/student/{student}", 
		"description" => "List of certificate requests for student", 
		"method" => "GET", 
		"code" => "return Certificate::forStudent(\$student);", 
		"acl" => "self(\$student)",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),
	
	array(
		"path" => "certificate/student/{student}", 
		"description" => "Request new certificate", 
		"method" => "POST", 
		"params" => array( "certificate" => "object" ),
		"classes" => array( "certificate" => "Certificate" ),
		"code" => "return Certificate::request(\$student, intval(\$certificate->CertificatePurpose), intval(\$certificate->CertificateType));", 
		"acl" => "self(\$student)",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),

	array(
		"path" => "certificate/purposesTypes", 
		"description" => "Codebook for certificate purposes and types", 
		"method" => "GET", 
		"code" => "return Certificate::purposesTypes();", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"certificate" => array("href" => "certificate/{id}"),
			"certificatesForStudent" => array("href" => "certificate/student/{student}"),
			"certificatePurposesTypes" => array("href" => "certificate/purposesTypes"),
		)
	),
	
	
	
	// ENROLLMENT
	
	array(
		"path" => "enrollment", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"currentEnrollment" => array("href" => "enrollment/current/{student}"),
			"allEnrollments" => array("href" => "enrollment/all/{student}"),
		)
	),
	
	array(
		"path" => "enrollment/current/{student}", 
		"description" => "Information about programme/semester that student is currently enrolled in", 
		"method" => "GET", 
		"code" => "return Enrollment::getCurrentForStudent(\$student);", 
		"acl" => "self(\$student) || privilege('studentska')",
		"hateoas_links" => array(
			"currentEnrollment" => array("href" => "enrollment/current/{student}"),
			"allEnrollments" => array("href" => "enrollment/all/{student}"),
		)
	),
	
	array(
		"path" => "enrollment/all/{student}", 
		"description" => "Information about programme/semester that student is currently enrolled in", 
		"method" => "GET", 
		"code" => "return Enrollment::getAllForStudent(\$student);", 
		"acl" => "self(\$student) || privilege('studentska')",
		"hateoas_links" => array(
			"currentEnrollment" => array("href" => "enrollment/current/{student}"),
			"allEnrollments" => array("href" => "enrollment/all/{student}"),
		)
	),
	
	
	
	// CURRICULUM
	
	array(
		"path" => "curriculum", 
		"description" => "List of hateoas links", 
		"method" => "GET", 
		"code" => "return new stdClass;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"curriculum" => array("href" => "curriculum/{id}"),
		)
	),
	
	array(
		"path" => "curriculum/{id}", 
		"description" => "Information about curriculum with list of courses", 
		"method" => "GET", 
		"params" => array( "get_courses" => "bool" ),
		"code" => "\$cur = Curriculum::fromId(\$id); if (\$get_courses) \$cur->courses = CurriculumCourse::forCurriculum(\$id); return \$cur;", 
		"acl" => "loggedIn()",
		"hateoas_links" => array(
			"curriculum" => array("href" => "curriculum/{id}"),
		)
	),
);

$ws_aliases = array(
);


?>
