# Class Diagram

This project uses **Mermaid** for the class diagram.

Mermaid is the best fit for this repository because it is text-based, easy to maintain in Git, renders directly in many Markdown viewers, and does not require extra tooling such as Java, Graphviz, or a UML desktop application.

```mermaid
classDiagram
    direction LR

    class User {
        +int id
        +string name
        +string email
        +string password
        +string role
        +bool must_reset_password
        +string profile_picture
        +bool show_email_publicly
        +isStudent()
        +isTeacher()
        +isAdmin()
        +teachingClasses()
        +classes()
        +enrolledClasses()
        +getProfilePictureUrlAttribute()
    }

    class ClassModel {
        +int id
        +string name
        +text description
        +int teacher_id
        +string join_code
        +string status
        +generateUniqueJoinCode()
        +regenerateJoinCode()
        +teacher()
        +students()
        +tps()
        +scopeActive()
        +scopeOwnedBy()
    }

    class TP {
        +int id
        +string title
        +text description
        +int teacher_id
        +int class_id
        +datetime due_date
        +string status
        +array attachments
        +teacher()
        +class()
        +submissions()
    }

    class Submission {
        +int id
        +int tp_id
        +int student_id
        +text content
        +array attachments
        +decimal grade
        +text teacher_comment
        +string status
        +datetime submitted_at
        +tp()
        +student()
    }

    class Attendance {
        +int id
        +int student_id
        +int class_id
        +int teacher_id
        +date date
        +string status
        +text notes
        +student()
        +class()
        +teacher()
    }

    class Post {
        +int id
        +int user_id
        +int class_id
        +int tp_id
        +string type
        +string title
        +text content
        +string attachment
        +user()
        +class()
        +tp()
        +comments()
        +likes()
        +isLikedBy()
        +visibleToStudent()
    }

    class Comment {
        +int id
        +int post_id
        +int user_id
        +int parent_id
        +text content
        +user()
        +post()
        +replies()
        +parent()
        +likes()
        +isLikedBy()
    }

    class Like {
        +int id
        +int user_id
        +int likeable_id
        +string likeable_type
        +likeable()
    }

    class Notification {
        +int id
        +int user_id
        +string type
        +string title
        +text message
        +string link
        +int related_id
        +bool is_read
        +user()
        +markAsRead()
        +createFor()
    }

    class NotificationSetting {
        +int id
        +int user_id
        +int class_id
        +bool new_tp_notifications
        +bool submission_graded_notifications
        +bool new_submission_notifications
        +bool post_notifications
        +bool student_joined_notifications
        +bool comment_notifications
        +bool like_notifications
        +bool comment_like_notifications
        +user()
        +courseClass()
        +getFor()
        +shouldNotify()
    }

    class Setting {
        +int id
        +string key
        +text value
        +string type
        +text description
        +get()
        +set()
    }

    class ClassStudent {
        <<pivot>>
        +int id
        +int class_id
        +int student_id
    }

    class Likeable {
        <<polymorphic>>
    }

    User "1" --> "0..*" ClassModel : teaches
    User "0..*" -- "0..*" ClassModel : enrolls via ClassStudent
    ClassStudent --> User : student_id
    ClassStudent --> ClassModel : class_id

    ClassModel "1" --> "0..*" TP : has
    TP "0..*" --> "1" User : teacher
    TP "0..*" --> "1" ClassModel : class
    TP "1" --> "0..*" Submission : receives
    Submission "0..*" --> "1" User : student

    Attendance "0..*" --> "1" User : student
    Attendance "0..*" --> "1" User : teacher
    Attendance "0..*" --> "1" ClassModel : class

    Post "0..*" --> "1" User : author
    Post "0..*" --> "0..1" ClassModel : class
    Post "0..*" --> "0..1" TP : related TP
    Post "1" --> "0..*" Comment : comments

    Comment "0..*" --> "1" User : author
    Comment "0..*" --> "1" Post : post
    Comment "0..*" --> "0..1" Comment : parent

    User "1" --> "0..*" Notification : receives
    NotificationSetting "0..*" --> "1" User : user
    NotificationSetting "0..*" --> "0..1" ClassModel : class

    Like "0..*" --> "1" User : user
    Like "0..*" --> "1" Likeable : likeable
    Post ..|> Likeable
    Comment ..|> Likeable
```

## Scope

This diagram focuses on the domain and persistence layer in `app/Models`. Controllers, mail classes, providers, and views are not included because they would make the diagram harder to read without adding much value to the core data model.
