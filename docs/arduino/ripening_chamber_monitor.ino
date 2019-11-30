#include <avr/pgmspace.h>
#include <avr/wdt.h>
#include <DHT.h>
#include <SPI.h>
#include <Ethernet.h>
#include <PubSubClient.h>
#define INTERVAL 2500
#define DHTTYPE DHT22

byte lastSensor = 0;
unsigned long readTime;
// Camara 1, Camara 2, Camara 3
const byte dhtPins[] = {9,8,7};
const byte leds[][3] = {
  // R,G,B
  {2,3,5},
  {16,15,14},
  {19,18,17}
};

const byte mac[] = { 0xDE,0xED,0xBA,0xFE,0xFE,0xED };
IPAddress ip(192,168,1,79);
IPAddress server(192,168,1,78);

const char led_cha1[] PROGMEM = "led/cha1";
const char led_cha2[] PROGMEM = "led/cha2";
const char led_cha3[] PROGMEM = "led/cha3";
const char* const sub_led[] PROGMEM = {led_cha1,led_cha2,led_cha3};

const char tem_cha1[] PROGMEM = "tem/cha1";
const char tem_cha2[] PROGMEM = "tem/cha2";
const char tem_cha3[] PROGMEM = "tem/cha3";
const char* const pub_tem[] PROGMEM = {tem_cha1,tem_cha2,tem_cha3};

const char hum_cha1[] PROGMEM = "hum/cha1";
const char hum_cha2[] PROGMEM = "hum/cha2";
const char hum_cha3[] PROGMEM = "hum/cha3";
const char* const pub_hum[] PROGMEM = {hum_cha1,hum_cha2,hum_cha3};

const char hic_cha1[] PROGMEM = "hic/cha1";
const char hic_cha2[] PROGMEM = "hic/cha2";
const char hic_cha3[] PROGMEM = "hic/cha3";
const char* const pub_hic[] PROGMEM = {hic_cha1,hic_cha2,hic_cha3};

const char status_topic[] PROGMEM = "dev";
const char id[] PROGMEM = "ard1";
const char* const connection[] PROGMEM = {status_topic,id};

char buffer[12];

EthernetClient client;
PubSubClient mqtt(client);
DHT* dht[sizeof(dhtPins)];

void blinkLeds(byte i) {
  Serial.println((String) "Blink leds camara: " + (i+1));
  for(byte j = 0; j < 3; j++) {
    digitalWrite(leds[i][j], HIGH);
    delay(500);
    digitalWrite(leds[i][j], LOW);
  }
}

void turnOffLeds() {
  for(byte i = 0; i < sizeof(dhtPins); i++) {
    for(byte j = 0; j < 3; j++) {
      digitalWrite(leds[i][j], LOW);
    }
  }
}

void stateLeds() {
  for(byte i = 0; i < sizeof(dhtPins); i++) {
    for(byte j = 0; j < 3; j++) {
      pinMode(leds[i][j], OUTPUT);
    }
  }
}

void switchLed(byte chamber, byte pin) {
  byte state;
  for (byte i = 0; i < 3; i++) {
    if (i == pin) {
      state = true;
    } else {
      state = false;
    }
    digitalWrite(leds[chamber][i], state);
    Serial.print((String) "Pin " + leds[chamber][i]);
    if (state) {
      Serial.println(" encendido");
    } else {
      Serial.println(" apagado");
    }
  }
  
}

void callback(char* topic, byte* payload, unsigned int length) {
  for(byte i = 0; i < sizeof(dhtPins); i++) {
    strcpy_P(buffer, (char*)pgm_read_word(&(sub_led[i])));
    if(strcmp(topic, buffer) == 0) {
      switch((char)payload[0]) {
        case 'r':
          switchLed(i, 0);
          break;
        case 'g':
          switchLed(i, 1);
          break;
        case 'b':
          switchLed(i, 2);
          break;
        default:
          blinkLeds(i);
      };
      break;
    }
  }
}

void reconnect() {
  while(!mqtt.connected()) {
    for(byte i = 0; i < sizeof(dhtPins); i++) {
      blinkLeds(i);
    }
    Ethernet.begin(mac, ip);
    delay(1500);
    strcpy_P(buffer, (char*)pgm_read_word(&(connection[1])));
    if(mqtt.connect(buffer)) {
      for(byte i = 0; i < sizeof(dhtPins); i++) {
        strcpy_P(buffer, (char*)pgm_read_word(&(sub_led[i])));
        mqtt.subscribe(buffer);
      }
    }
    else {
      delay(1000);
    }
  }
}

void sensorRead(byte i) {
  readTime = millis();
  Serial.println((String) "Leyendo sensor: " + i);
  if (dht[i]->read()) {
    float h = dht[i]->readHumidity();
    float t = dht[i]->readTemperature();
    strcpy_P(buffer, (char*)pgm_read_word(&(pub_tem[i])));
    mqtt.publish(buffer, String(t).c_str());
  
    strcpy_P(buffer, (char*)pgm_read_word(&(pub_hum[i])));
    mqtt.publish(buffer, String(h).c_str());
  
    strcpy_P(buffer, (char*)pgm_read_word(&(pub_hic[i])));
    mqtt.publish(buffer, String(dht[i]->computeHeatIndex(t, h, false)).c_str());
    
    Serial.print(t, 1);
    Serial.print("\t");
    Serial.print(h, 1);
    Serial.print("\t\t");
    Serial.println(dht[i]->computeHeatIndex(t, h, false), 1);
  } else {
    blinkLeds(i);
  }
}

void sendStatus() {
  strcpy_P(buffer, (char*)pgm_read_word(&(connection[0])));
  strcat(buffer, "/");
  strcat_P(buffer, (char*)pgm_read_word(&(connection[1])));
  mqtt.publish(buffer, String(1).c_str());
}

void setup()
{
  Serial.begin(9600);
  wdt_disable();
  stateLeds();
  turnOffLeds();
  mqtt.setServer(server, 1883);
  mqtt.setCallback(callback);
  for (byte i = 0; i < sizeof(dhtPins); i++) {
    pinMode(dhtPins[i], INPUT);
    dht[i] = new DHT(dhtPins[i], DHTTYPE);
    dht[i]->begin();
  };
  delay(1500);
  readTime = 0;
  wdt_enable(WDTO_8S);
}

void loop()
{
  if(!mqtt.loop()) {
    reconnect();
  }
  else {
    wdt_reset();
    if(millis() > readTime + INTERVAL){
      sensorRead(lastSensor);
      lastSensor++;
      if (lastSensor >= sizeof(dhtPins)) {
        lastSensor = 0;
      }
      sendStatus();
    }
    mqtt.loop();
  }
}