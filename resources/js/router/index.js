import { createRouter, createWebHistory } from 'vue-router';

// Import layouts
import DashboardLayout from '../layouts/DashboardLayout.vue';

// Import views
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard/Index.vue';
import KursusIndex from '../views/Kursus/Index.vue';
import KursusShow from '../views/Kursus/Show.vue';
import ProfileEdit from '../views/Profile/Edit.vue';
import MyCoursesIndex from '../views/MyCourses/Index.vue';
import CertificatesIndex from '../views/Certificates/Index.vue';

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { guest: true }
  },
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard',
        component: Dashboard
      },
      {
        path: '/kursus',
        name: 'kursus.index',
        component: KursusIndex
      },
      {
        path: '/kursus/:id',
        name: 'kursus.show',
        component: KursusShow
      },
      {
        path: '/profile',
        name: 'profile.edit',
        component: ProfileEdit
      },
      {
        path: '/my-courses',
        name: 'my-courses',
        component: MyCoursesIndex
      },
      {
        path: '/certificates',
        name: 'certificates',
        component: CertificatesIndex
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!token) {
      next({ name: 'login' });
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.guest)) {
    if (token) {
      next({ name: 'dashboard' });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
