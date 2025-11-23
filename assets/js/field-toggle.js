
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('select[name="role"]');
        const studentFields = document.getElementById('student-fields');
        const teacherFields = document.getElementById('teacher-fields');

        function toggleFields() {
            // 1. Close both first (Remove 'open' class)
            studentFields.classList.remove('open');
            teacherFields.classList.remove('open');

            // 2. Reset All Required attributes
            document.querySelectorAll('#dynamic-fields input, #dynamic-fields select').forEach(el => {
                el.required = false;
            });

            // 3. Open based on selection (Add 'open' class)
            if (roleSelect.value === 'student') {
                studentFields.classList.add('open');
                
                // Set Required for Student
                document.querySelector('input[name="student_id"]').required = true;
                document.querySelector('select[name="course"]').required = true;
                document.querySelector('select[name="year_level"]').required = true;
                
            } else if (roleSelect.value === 'teacher') {
                teacherFields.classList.add('open');
                
                // Set Required for Teacher
                document.querySelector('select[name="department"]').required = true;
                document.querySelector('select[name="academic_rank"]').required = true;
            }
        }

        roleSelect.addEventListener('change', toggleFields);
    });