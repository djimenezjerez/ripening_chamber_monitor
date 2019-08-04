module.exports = (sequelize, DataTypes) => {
  let Measurement = sequelize.define('Measurement', {
    value: DataTypes.FLOAT
  }, {
    tableName: 'measurements',
    underscored: true
  })

  return Measurement
}