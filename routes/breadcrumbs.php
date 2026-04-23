<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;
use App\Models\Post;
use App\Models\ClassModel;
use App\Models\TP;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Str;

// ─── ADMIN ───────────────────────────────────────────────
Breadcrumbs::for('admin.users.index', fn (Trail $t) =>
    $t->push('Utilisateurs', route('admin.users.index'))
);

Breadcrumbs::for('admin.classes.index', fn (Trail $t) =>
    $t->push('Classes', route('admin.classes.index'))
);

Breadcrumbs::for('admin.classes.show', function (Trail $t, $class) {
    $class = is_object($class) ? $class : ClassModel::find($class);
    $t->parent('admin.classes.index')->push($class->name);
});

Breadcrumbs::for('admin.statistics', fn (Trail $t) =>
    $t->push('Statistiques', route('admin.statistics'))
);

Breadcrumbs::for('admin.system-logs', fn (Trail $t) =>
    $t->push('Logs Système', route('admin.system-logs'))
);

Breadcrumbs::for('admin.settings.index', fn (Trail $t) =>
    $t->push('Paramètres', route('admin.settings.index'))
);

// ─── TEACHER ─────────────────────────────────────────────
Breadcrumbs::for('teacher.dashboard', fn (Trail $t) =>
    $t->push('Fil d\'actualité', route('feed.index'))
);
Breadcrumbs::for('teacher.courses.index', fn (Trail $t) =>
    $t->push('Mes cours', route('teacher.courses.index'))
);
Breadcrumbs::for('teacher.courses.create', fn (Trail $t) =>
    $t->parent('teacher.courses.index')->push('Créer un Nouveau Cours', route('teacher.courses.create'))
);
Breadcrumbs::for('teacher.courses.show', function (Trail $t, $course) {
    $course = is_object($course) ? $course : ClassModel::find($course);
    $t->parent('teacher.courses.index')->push($course->name, route('teacher.courses.show', $course->id));
});
Breadcrumbs::for('teacher.tps.show', function (Trail $t, $tp) {
    $tp = is_object($tp) ? $tp : TP::find($tp);
    $t->parent('teacher.courses.show', $tp->class_id)
      ->push(Str::limit($tp->title, 40), route('teacher.tps.show', $tp->id));
});
Breadcrumbs::for('teacher.submissions.show', function (Trail $t, $submission) {
    $submission = is_object($submission) ? $submission : Submission::with(['tp', 'student'])->find($submission);
    if (!$submission || !$submission->tp_id) return;
    $t->parent('teacher.tps.show', $submission->tp_id)
      ->push('Soumission de ' . $submission->student->name);
});
Breadcrumbs::for('teacher.progress.index', fn (Trail $t) =>
    $t->push('Suivi étudiants', route('teacher.progress.index'))
);
Breadcrumbs::for('teacher.progress.show', function (Trail $t, $student) {
    $student = is_object($student) ? $student : User::find($student);
    $t->parent('teacher.progress.index')->push($student->name);
});
Breadcrumbs::for('teacher.attendance.index', fn (Trail $t) =>
    $t->push('Présences')
);
Breadcrumbs::for('teacher.statistics', fn (Trail $t) =>
    $t->push('Statistiques')
);


// ─── STUDENT ─────────────────────────────────────────────
Breadcrumbs::for('student.dashboard', fn (Trail $t) =>
    $t->push('Tableau de bord', route('student.dashboard'))
);
Breadcrumbs::for('student.my-courses', fn (Trail $t) =>
    $t->push('Mes cours', route('student.my-courses'))
);
Breadcrumbs::for('student.courses.show', function (Trail $t, $course) {
    $course = is_object($course) ? $course : ClassModel::find($course);
    $t->parent('student.my-courses')->push($course->name, route('student.courses.show', $course->id));
});
Breadcrumbs::for('student.tps.show', function (Trail $t, $tp) {
    $tp = is_object($tp) ? $tp : TP::find($tp);
    $t->parent('student.courses.show', $tp->class_id)
      ->push(Str::limit($tp->title, 40));
});
Breadcrumbs::for('student.submissions.index', fn (Trail $t) =>
    $t->push('Mes soumissions')
);
Breadcrumbs::for('student.progress', fn (Trail $t) =>
    $t->push('Ma progression')
);

// ─── SHARED ──────────────────────────────────────────────
Breadcrumbs::for('posts.show', function (Trail $t, $post) {
    $post = is_object($post) ? $post : Post::find($post);
    $t->parent('teacher.dashboard')->push('Publication');
});