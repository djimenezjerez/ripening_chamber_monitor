module.exports = (sequelize, DataTypes) => {
  let Device = sequelize.define('Device', {
    name: DataTypes.STRING,
    display_name: DataTypes.STRING,
    mac: DataTypes.STRING,
    ip: DataTypes.STRING,
    online: DataTypes.BOOLEAN
  }, {
    underscored: true
  })

  return Device
}