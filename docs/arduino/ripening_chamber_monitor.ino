#include <avr/pgmspace.h>
#include <avr/wdt.h>
#include <DHT.h>
#include <SPI.h>
#include <Ethernet.h>
#include <PubSubClient.h>
#define INTERVAL 2000
#define DHTTYPE DHT22

unsigned long readTime;
const byte dhtPins[] = {2};
const byte leds[][3] = {
  {18,16,17},
  {3,5,6},
  {7,8,9}
};

const byte mac[] = { 0xDE,0xED,0xBA,0xFE,0xFE,0xED };
IPAddress ip(172,16,1,100);
IPAddress server(172,16,1,1);

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

const char id[] PROGMEM = "ard1";
const char* const connection[] PROGMEM = {id};

char buffer[12];

EthernetClient client;
PubSubClient mqtt(client);
DHT* dht[sizeof(dhtPins)];

void blinkLeds(byte i) {
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

void callback(char* topic, byte* payload, unsigned int length) {
  turnOffLeds();
  for(byte i = 0; i < sizeof(dhtPins); i++) {
    strcpy_P(buffer, (char*)pgm_read_word(&(sub_led[i])));
    if(strcmp(topic, buffer) == 0) {
      switch((char)payload[i]) {
        case 'r':
          digitalWrite(leds[i][0], HIGH);
          break;
        case 'g':
          digitalWrite(leds[i][1], HIGH);
          break;
        case 'b':
          digitalWrite(leds[i][2], HIGH);
          break;
        default:
          blinkLeds(i);
          break;
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
    strcpy_P(buffer, (char*)pgm_read_word(&(connection[0])));
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

void sensorsRead() {
  for (byte i = 0; i < sizeof(dhtPins); i++) {
    sensorRead(i);
  };
}

void sensorRead(byte i) {
  readTime = millis();
  float h = dht[i]->readHumidity();
  float t = dht[i]->readTemperature();

  if (isnan(h) || isnan(t)) {
    blinkLeds(i);
    return;
  }

  strcpy_P(buffer, (char*)pgm_read_word(&(pub_tem[i])));
  mqtt.publish(buffer, String(t).c_str());

  strcpy_P(buffer, (char*)pgm_read_word(&(pub_hum[i])));
  mqtt.publish(buffer, String(h).c_str());

  strcpy_P(buffer, (char*)pgm_read_word(&(pub_hic[i])));
  mqtt.publish(buffer, String(dht[i]->computeHeatIndex(t, h, false)).c_str());
}

void setup()
{
  wdt_disable();
  stateLeds();
  turnOffLeds();
  mqtt.setServer(server, 1883);
  mqtt.setCallback(callback);
  for (byte i = 0; i < sizeof(dhtPins); i++) {
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
      sensorsRead();
    }
    mqtt.loop();
  }
}
