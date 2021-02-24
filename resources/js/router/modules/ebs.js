/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const ebsRoutes = {
  path: '/ebs',
  component: Layout,
  redirect: 'noredirect',
  name: 'EBS',
  meta: {
    title: 'ebs',
    icon: 'list',
    permissions: ['read ebs'],
  },
  children: [
    {
      path: '/batteries',
      component: () => import('@/views/ebs/Battery'),
      name: 'Battery',
      meta: {
        title: 'batteries',
        icon: 'list',
        permissions: ['create ebs', 'read ebs', 'update ebs', 'delete ebs'],
      },
    },
    {
      path: '/battery-manufacturers',
      component: () => import('@/views/ebs/BatteryManufacturer'),
      name: 'Battery Manufacturer',
      meta: {
        title: 'batteryManufacturers',
        icon: 'list',
        permissions: ['create ebs', 'read ebs', 'update ebs', 'delete ebs'],
      },
    },
    {
      path: '/cars',
      component: () => import('@/views/ebs/Car'),
      name: 'Car',
      meta: {
        title: 'cars',
        icon: 'list',
        permissions: ['create ebs', 'read ebs', 'update ebs', 'delete ebs'],
      },
    },
    {
      path: '/cars/:carID(\\d+)/batteries',
      component: () => import('@/views/ebs/CarBattery'),
      name: 'Car Battery',
      hidden: true,
      meta: {
        prevPath: ['/cars'],
        prevBreadcrumb: ['cars'],
        title: 'carBatteries',
        noCache: true,
        permissions: ['create ebs', 'read ebs', 'update ebs', 'delete ebs'],
      },
    },
    {
      path: '/car-manufacturers',
      component: () => import('@/views/ebs/CarManufacturer'),
      name: 'Car Manufacturer',
      meta: {
        title: 'carManufacturers',
        icon: 'list',
        permissions: ['create ebs', 'read ebs', 'update ebs', 'delete ebs'],
      },
    },
    {
      path: '/clients',
      component: () => import('@/views/ebs/Client'),
      name: 'EBS Client',
      meta: {
        title: 'clients',
        icon: 'list',
        permissions: ['create ebs', 'read ebs', 'update ebs', 'delete ebs'],
      },
    },
  ],
};

export default ebsRoutes;
