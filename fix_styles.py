import re

# Fix 1
f = 'resources/views/admin/classes/create.blade.php'
with open(f, 'r') as fp:
    c = fp.read()
c = re.sub(r'color: white;\s*}\s*\.btn-secondary:hover\s*{\s*background-color: var\(--color-text-muted\);\s*}\s*</style>', '', c, flags=re.DOTALL)
with open(f, 'w') as fp:
    fp.write(c)

# Fix 3
def rep(filename, replacements):
    with open(filename, 'r') as fp:
        c = fp.read()
    for old, new in replacements.items():
        c = c.replace(old, new)
    with open(filename, 'w') as fp:
        fp.write(c)

rep('resources/views/teacher/courses/show.blade.php', {'"info-card"': '"info-card-stat"'})
rep('resources/views/student/courses/show.blade.php', {'"info-card"': '"info-card-stat"'})
rep('resources/views/admin/system-logs.blade.php', {'"info-grid"': '"info-grid-sys"', '"info-item"': '"info-item-sys"'})
rep('resources/views/admin/settings/index.blade.php', {'"info-text"': '"info-box"', '"checkbox-group"': '"checkbox-item"'})

