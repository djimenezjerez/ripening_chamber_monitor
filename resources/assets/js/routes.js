import Login from './components/auth/Login'
import Profile from './components/auth/Profile'
import DashboardIndex from './components/dashboard/Index'
import UserIndex from './components/user/Index'
import RoleIndex from './components/role/Index'
import RoomIndex from './components/room/Index'
import DeviceIndex from './components/device/Index'
import MagnitudeIndex from './components/magnitude/Index'
import ReportIndex from './components/report/Index'

export const routes = [
  {
    name: 'login',
    path: '/login',
    component: Login
  }, {
    name: 'profile',
    path: '/profile',
    component: Profile,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '*',
    redirect: {
      name: 'dashboardIndex'
    },
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/dashboard',
    name: 'dashboardIndex',
    component: DashboardIndex,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/user',
    name: 'userIndex',
    component: UserIndex,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/role',
    name: 'roleIndex',
    component: RoleIndex,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/room',
    name: 'roomIndex',
    component: RoomIndex,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/device',
    name: 'deviceIndex',
    component: DeviceIndex,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/magnitude',
    name: 'magnitudeIndex',
    component: MagnitudeIndex,
    meta: {
      requiresAuth: true
    }
  }, {
    path: '/report',
    name: 'reportIndex',
    component: ReportIndex,
    meta: {
      requiresAuth: true
    }
  }
]