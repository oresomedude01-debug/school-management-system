<?php

return [
    'app_name' => 'أكاديمية التميز',
    'app_tagline' => 'الأكاديمية',

    // Navigation
    'nav' => [
        'dashboard' => 'لوحة التحكم',
        'students' => 'الطلاب',
        'teachers' => 'المعلمون',
        'classes' => 'الفصول',
        'subjects' => 'المواد',
        'attendance' => 'الحضور',
        'grades' => 'الدرجات',
        'more' => 'المزيد',
        'home' => 'الرئيسية',
        'attend' => 'حضور',
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'نظرة عامة على لوحة التحكم',
        'welcome' => 'مرحباً بعودتك! 👋',
        'loading' => 'جاري تحميل التميز...',
        'total_students' => 'إجمالي الطلاب',
        'total_teachers' => 'إجمالي المعلمين',
        'total_classes' => 'إجمالي الفصول',
        'total_subjects' => 'إجمالي المواد',
        'attendance_rate' => 'معدل الحضور',
        'average_grade' => 'متوسط الدرجات',
    ],

    // Topbar
    'topbar' => [
        'search_placeholder' => 'ابحث عن الطلاب، المعلمين، الفصول...',
        'search_mobile_placeholder' => 'ابحث عن أي شيء...',
        'notifications' => 'الإشعارات',
        'messages' => 'الرسائل',
        'profile' => 'الملف الشخصي',
        'settings' => 'الإعدادات',
        'preferences' => 'التفضيلات',
        'logout' => 'تسجيل الخروج',
        'admin' => 'المدير',
    ],

    // User Profile
    'user' => [
        'administrator' => 'المدير',
        'admin_email' => 'admin@school.com',
        'profile_settings' => 'إعدادات الملف الشخصي',
        'preferences' => 'التفضيلات',
    ],

    // Students
    'students' => [
        'title' => 'إدارة الطلاب',
        'add_student' => 'إضافة طالب',
        'edit_student' => 'تعديل طالب',
        'delete_student' => 'حذف طالب',
        'student_details' => 'تفاصيل الطالب',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'class' => 'الفصل',
        'date_of_birth' => 'تاريخ الميلاد',
        'phone' => 'الهاتف',
        'address' => 'العنوان',
        'enrollment_date' => 'تاريخ التسجيل',
        'status' => 'الحالة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
    ],

    // Teachers
    'teachers' => [
        'title' => 'إدارة المعلمين',
        'add_teacher' => 'إضافة معلم',
        'edit_teacher' => 'تعديل معلم',
        'delete_teacher' => 'حذف معلم',
        'teacher_details' => 'تفاصيل المعلم',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'subject' => 'المادة',
        'phone' => 'الهاتف',
        'address' => 'العنوان',
        'qualification' => 'المؤهل',
        'experience' => 'الخبرة',
        'joining_date' => 'تاريخ الانضمام',
    ],

    // Classes
    'classes' => [
        'title' => 'إدارة الفصول',
        'add_class' => 'إضافة فصل',
        'edit_class' => 'تعديل فصل',
        'delete_class' => 'حذف فصل',
        'class_details' => 'تفاصيل الفصل',
        'class_name' => 'اسم الفصل',
        'teacher' => 'معلم الفصل',
        'room_number' => 'رقم الغرفة',
        'capacity' => 'السعة',
        'students_count' => 'عدد الطلاب',
    ],

    // Subjects
    'subjects' => [
        'title' => 'إدارة المواد',
        'add_subject' => 'إضافة مادة',
        'edit_subject' => 'تعديل مادة',
        'delete_subject' => 'حذف مادة',
        'subject_details' => 'تفاصيل المادة',
        'subject_name' => 'اسم المادة',
        'code' => 'رمز المادة',
        'description' => 'الوصف',
        'credits' => 'الساعات المعتمدة',
    ],

    // Attendance
    'attendance' => [
        'title' => 'إدارة الحضور',
        'mark_attendance' => 'تسجيل الحضور',
        'view_attendance' => 'عرض الحضور',
        'date' => 'التاريخ',
        'student' => 'الطالب',
        'status' => 'الحالة',
        'present' => 'حاضر',
        'absent' => 'غائب',
        'late' => 'متأخر',
        'excused' => 'معذور',
    ],

    // Grades
    'grades' => [
        'title' => 'إدارة الدرجات',
        'add_grade' => 'إضافة درجة',
        'edit_grade' => 'تعديل درجة',
        'view_report' => 'عرض التقرير',
        'student' => 'الطالب',
        'subject' => 'المادة',
        'exam_type' => 'نوع الامتحان',
        'marks' => 'الدرجات',
        'grade' => 'التقدير',
        'remarks' => 'ملاحظات',
        'midterm' => 'منتصف الفصل',
        'final' => 'نهائي',
    ],

    // Common Actions
    'actions' => [
        'add' => 'إضافة',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'search' => 'بحث',
        'filter' => 'تصفية',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'view' => 'عرض',
        'back' => 'رجوع',
        'next' => 'التالي',
        'previous' => 'السابق',
        'close' => 'إغلاق',
    ],

    // Messages
    'messages' => [
        'success' => 'تمت العملية بنجاح',
        'error' => 'حدث خطأ',
        'confirm_delete' => 'هل أنت متأكد من حذف هذا العنصر؟',
        'no_data' => 'لا توجد بيانات متاحة',
        'loading' => 'جاري التحميل...',
    ],

    // Language
    'language' => [
        'select' => 'اختر اللغة',
        'english' => 'English',
        'arabic' => 'العربية',
        'current' => 'ع',
    ],
];
