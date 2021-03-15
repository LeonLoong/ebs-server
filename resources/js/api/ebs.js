import request from '@/utils/request';

export function getBatteries(query) {
  return request({
    url: '/ebs/batteries/',
    method: 'get',
    params: query,
  });
}

export function storeBattery(resource) {
  return request({
    url: '/ebs/batteries/',
    method: 'post',
    data: resource,
  });
}

export function getBatteryManufacturers(query) {
  return request({
    url: '/ebs/battery-manufacturers/',
    method: 'get',
    params: query,
  });
}

export function getBatterySeries(query) {
  return request({
    url: '/ebs/battery-series/',
    method: 'get',
    params: query,
  });
}

export function getBatteryTypes(query) {
  return request({
    url: '/ebs/battery-types/',
    method: 'get',
    params: query,
  });
}

export function getBatteryTradeIns(query) {
  return request({
    url: '/ebs/battery-trade-ins/',
    method: 'get',
    params: query,
  });
}

export function getCars(query) {
  return request({
    url: '/ebs/cars/',
    method: 'get',
    params: query,
  });
}

export function storeCar(resource) {
  return request({
    url: '/ebs/cars/',
    method: 'post',
    data: resource,
  });
}

export function updateCar(carID, resource) {
  return request({
    url: '/ebs/cars/' + carID,
    method: 'put',
    data: resource,
  });
}

export function destroyCar(carID) {
  return request({
    url: '/ebs/cars/' + carID,
    method: 'delete',
  });
}

export function getCarManufacturers(query) {
  return request({
    url: '/ebs/car-manufacturers/',
    method: 'get',
    params: query,
  });
}

export function storeCarManufacturer(resource) {
  return request({
    url: '/ebs/car-manufacturers/',
    method: 'post',
    data: resource,
  });
}

export function getCarBatteries(query, carID) {
  return request({
    url: '/ebs/cars/' + carID + '/batteries/',
    method: 'get',
    params: query,
  });
}

export function getClients(query) {
  return request({
    url: '/ebs/clients/',
    method: 'get',
    params: query,
  });
}
