module.exports = (sequelize, DataTypes) => {
  let Room = sequelize.define('Room', {
    name: DataTypes.STRING,
    display_name: DataTypes.STRING,
    device_id: DataTypes.INTEGER
  }, {
    underscored: true
  })

  Room.associate = function(models) {
    models.Room.belongsToMany(models.Magnitude, {
      through: models.MagnitudeRoom,
      foreignKey: 'room_id',
      otherKey: 'magnitude_id',
      as: 'magnitudes',
      timestamps: false
    })
    models.Room.belongsToMany(models.Magnitude, {
      through: models.Measurement,
      foreignKey: 'room_id',
      otherKey: 'magnitude_id',
      as: 'measurements',
      timestamps: false
    })
  }

  return Room
}