#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
WiFiClient client; 

const char* ssid     = "Nokia 5.1 Plus";
const char* password = "12345678";
const char* host     = "192.168.43.153";
String path          = "/smart_class/otorisasi_d205.php";

String data;
int index1, index2;
String dat1="";

void setup() {
  // put your setup code here, to run once:
  pinMode(D1, INPUT);
  pinMode(D2, INPUT);
  pinMode(D6, OUTPUT);
  digitalWrite(D6, HIGH);
  
  Serial.begin(115200);
  delay(10);
  Serial.print ("Connecting to ");
  Serial.println (ssid);

  WiFi.begin(ssid, password);
  
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print (".");
  }
  Serial.println("");
  Serial.println("WiFi Connected"); 
  Serial.println ("IP address " + WiFi.localIP());
  Serial.print("connecting to");
  Serial.println(host);
}

void loop() {
  // put your main code here, to run repeatedly:
  WiFi.begin(ssid, password);
  
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print (".");
  }
  Serial.println(digitalRead(D1));
  Serial.println(digitalRead(D2));
  delay(1000);

  
  if(!client.connect(host, 80)){
      Serial.println ("connection failed");
      return;    
    }
    
    if(digitalRead(D2) == 0){
    delay(2000);
    client.print(String("GET ") + path + " HTTP/1.1\r\n" + "Host: " + host + "\r\n" + "Connection: keep-alive\r\n\r\n");
    delay(1000); // wait for server to respond
  
  
    while(client.available()){
      delay(10);
      char c = client.read();
      data+=c;
    }
    if (data.length() > 0){
      //Serial.println(data);
      index1 = data.indexOf("%");
      index2 = data.indexOf("%", index1 + 1);
      dat1 = data.substring(index1 + 1, index2);
      Serial.print("dat1:");
      Serial.println(dat1);
      data = "";
      if (dat1 == " Otorisasi berhasil"){
        Serial.println("Pintu Terbuka");
        digitalWrite(D6, LOW);
        delay(3000);
      }
    }
  }
  else{
    Serial.println("Autentikasi gagal");
    digitalWrite(D6, HIGH);
  }
}
