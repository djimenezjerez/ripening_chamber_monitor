module.exports = (sequelize, DataTypes) => {
  let MagnitudeRoom = sequelize.define('MagnitudeRoom', {
    min_limit: DataTypes.FLOAT,
    max_limit: DataTypes.FLOAT,
    interval: DataTypes.INTEGER
  }, {
    tableName: 'magnitude_room',
    underscored: true,
    timestamps: false
  })

  return MagnitudeRoom
}