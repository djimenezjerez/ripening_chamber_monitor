module.exports = (sequelize, DataTypes) => {
  let Magnitude = sequelize.define('Magnitude', {
    name: DataTypes.STRING,
    display_name: DataTypes.STRING,
    measure: DataTypes.STRING
  }, {
    underscored: true
  })

  Magnitude.associate = function(models) {
    models.Magnitude.belongsToMany(models.Room, {
      through: models.MagnitudeRoom,
      foreignKey: 'magnitude_id',
      otherKey: 'room_id',
      as: 'rooms',
      timestamps: false
    })
    models.Magnitude.belongsToMany(models.Room, {
      through: models.Measurement,
      foreignKey: 'magnitude_id',
      otherKey: 'room_id',
      as: 'measurements',
      timestamps: false
    })
  }

  return Magnitude
}