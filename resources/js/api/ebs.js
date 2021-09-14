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

/* Since HTML forms can't make PUT, PATCH, or DELETE requests,
 you will need to add a hidden _method field to spoof these HTTP verbs. */
export function updateBattery(batteryID, resource) {
  return request({
    url: '/ebs/batteries/' + batteryID,
    method: 'post',
    data: resource,
  });
}

export function destroyBattery(batteryID) {
  return request({
    url: '/ebs/batteries/' + batteryID,
    method: 'delete',
  });
}

export function getBatteryManufacturers(query) {
  return request({
    url: '/ebs/battery-manufacturers/',
    method: 'get',
    params: query,
  });
}

export function storeBatteryManufacturer(resource) {
  return request({
    url: '/ebs/battery-manufacturers/',
    method: 'post',
    data: resource,
  });
}

/* Since HTML forms can't make PUT, PATCH, or DELETE requests,
 you will need to add a hidden _method field to spoof these HTTP verbs. */
export function updateBatteryManufacturer(batteryManufacturerID, resource) {
  return request({
    url: '/ebs/battery-manufacturers/' + batteryManufacturerID,
    method: 'post',
    data: resource,
  });
}

export function destroyBatteryManufacturer(batteryManufacturerID) {
  return request({
    url: '/ebs/battery-manufacturers/' + batteryManufacturerID,
    method: 'delete',
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

export function getCar(carID) {
  return request({
    url: '/ebs/cars/' + carID,
    method: 'get',
  });
}

export function storeCar(resource) {
  return request({
    url: '/ebs/cars/',
    method: 'post',
    data: resource,
  });
}

/* Since HTML forms can't make PUT, PATCH, or DELETE requests,
 you will need to add a hidden _method field to spoof these HTTP verbs. */
export function updateCar(carID, resource) {
  return request({
    url: '/ebs/cars/' + carID,
    method: 'post',
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

/* Since HTML forms can't make PUT, PATCH, or DELETE requests,
 you will need to add a hidden _method field to spoof these HTTP verbs. */
export function updateCarManufacturer(carManufacturerID, resource) {
  return request({
    url: '/ebs/car-manufacturers/' + carManufacturerID,
    method: 'post',
    data: resource,
  });
}

export function destroyCarManufacturer(carManufacturerID) {
  return request({
    url: '/ebs/car-manufacturers/' + carManufacturerID,
    method: 'delete',
  });
}

export function getCarBatteries(query, carID) {
  return request({
    url: '/ebs/cars/' + carID + '/batteries/',
    method: 'get',
    params: query,
  });
}

export function getTransactionRecords(query) {
  return request({
    url: '/ebs/transaction-records/',
    method: 'get',
    params: query,
  });
}

export function storeTransactionRecord(resource) {
  return request({
    url: '/ebs/transaction-records/',
    method: 'post',
    data: resource,
  });
}

/* Since HTML forms can't make PUT, PATCH, or DELETE requests,
 you will need to add a hidden _method field to spoof these HTTP verbs. */
export function updateTransactionRecord(transactionRecordID, resource) {
  return request({
    url: '/ebs/transaction-records/' + transactionRecordID,
    method: 'post',
    data: resource,
  });
}

export function destroyTransactionRecord(transactionRecordID) {
  return request({
    url: '/ebs/transaction-records/' + transactionRecordID,
    method: 'delete',
  });
}

export function getPaymentMethods(query) {
  return request({
    url: '/ebs/payment-methods/',
    method: 'get',
    params: query,
  });
}

