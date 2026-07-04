<?php

$mapping = [
    'AcademicSessionPolicy' => ['all' => 'manage-academic-sessions'],
    'DepartmentPolicy' => ['all' => 'manage-departments'],
    'GradeSettingPolicy' => ['all' => 'manage-grade-settings'],
    'InvoiceCustomerNamePolicy' => ['all' => 'manage-invoices'],
    'SchoolClassPolicy' => ['all' => 'manage-classes'],
    'SubjectPolicy' => ['all' => 'manage-subjects'],
    'TermPolicy' => ['all' => 'manage-academic-sessions'],
    'AttendancePolicy' => [
        'viewAny' => 'view-attendance',
        'view' => 'view-attendance',
        'create' => 'manage-attendance',
        'update' => 'manage-attendance',
        'delete' => 'manage-attendance',
        'restore' => 'manage-attendance',
        'forceDelete' => 'manage-attendance',
    ],
    'ResultPolicy' => [
        'viewAny' => 'view-results',
        'view' => 'view-results',
        'create' => 'upload-results',
        'update' => 'upload-results',
        'delete' => 'upload-results',
        'restore' => 'upload-results',
        'forceDelete' => 'upload-results',
    ],
];

foreach ($mapping as $policy => $perms) {
    $file = 'app/Policies/'.$policy.'.php';
    if (! file_exists($file)) {
        continue;
    }

    $content = file_get_contents($file);

    if (isset($perms['all'])) {
        $perm = $perms['all'];
        $content = preg_replace('/return false;/', "return \$user->can('$perm');", $content);
    } else {
        foreach ($perms as $method => $perm) {
            $regex = '/(public function '.$method.'\s*\([^)]*\)\s*:\s*bool\s*\{[\s\n\r]*)(return false;)/';
            $content = preg_replace($regex, "$1return \$user->can('$perm');", $content);
        }
    }

    file_put_contents($file, $content);
    echo "Updated $policy\n";
}

// Special case for UserPolicy
$userPolicy = 'app/Policies/UserPolicy.php';
if (file_exists($userPolicy)) {
    $content = file_get_contents($userPolicy);
    // Allow users to view themselves, and admins/teachers to view users based on roles/perms
    $content = preg_replace(
        '/(public function viewAny\s*\([^)]*\)\s*:\s*bool\s*\{[\s\n\r]*)(return false;)/',
        "$1return \$user->hasAnyRole(['admin', 'teacher']) || \$user->can('manage-users') || \$user->can('manage-students') || \$user->can('manage-teachers');",
        $content
    );

    $content = preg_replace(
        '/(public function view\s*\(User \$user, User \$model\)\s*:\s*bool\s*\{[\s\n\r]*)(return false;)/',
        "$1return \$user->id === \$model->id || \$user->hasAnyRole(['admin', 'teacher']) || \$user->can('manage-users');",
        $content
    );

    $content = preg_replace(
        '/(public function (?:create|update|delete|restore|forceDelete)\s*\([^)]*\)\s*:\s*bool\s*\{[\s\n\r]*)(return false;)/',
        "$1return \$user->id === \$model->id || \$user->can('manage-users') || \$user->can('manage-students') || \$user->can('manage-teachers');",
        $content
    );

    file_put_contents($userPolicy, $content);
    echo "Updated UserPolicy\n";
}
