# Workspace API

Workspace create/list/update dikelola owner. Membership menggunakan role `owner`, `editor`, dan `viewer`; role menentukan policy per resource. Semua endpoint menerima workspace secara eksplisit di route atau header terstandar, tidak melalui global mutable state.
