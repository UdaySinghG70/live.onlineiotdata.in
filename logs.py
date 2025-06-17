import paho.mqtt.client as mqtt
import time
from datetime import datetime

# MQTT Configuration
MQTT_BROKER = "103.212.120.23"
MQTT_PORT = 1883
MQTT_USERNAME = "admin"
MQTT_PASSWORD = "BeagleBone99"

# Topics to subscribe to
TOPICS = [
    "server/logs/mqtt",
    "server/logs/watchdog",
    "server/logs/websocket",
    "server/logs/status"
]

def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print(f"[{datetime.now()}] Successfully connected to MQTT broker")
        # Subscribe to all log topics
        for topic in TOPICS:
            client.subscribe(topic)
            print(f"[{datetime.now()}] Subscribed to {topic}")
    else:
        print(f"[{datetime.now()}] Failed to connect to MQTT broker. Return code: {rc}")

def on_disconnect(client, userdata, rc):
    print(f"[{datetime.now()}] Disconnected from MQTT broker with code: {rc}")
    if rc != 0:
        print(f"[{datetime.now()}] Attempting to reconnect...")
        client.reconnect()

def on_message(client, userdata, msg):
    timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    topic = msg.topic
    try:
        payload = msg.payload.decode()
        print(f"\n[{timestamp}] {topic}")
        print("-" * 80)
        print(payload)
        print("-" * 80)
    except Exception as e:
        print(f"[{timestamp}] Error processing message: {str(e)}")

def main():
    # Create MQTT client
    client = mqtt.Client()
    
    # Set callbacks
    client.on_connect = on_connect
    client.on_disconnect = on_disconnect
    client.on_message = on_message
    
    # Set username and password
    client.username_pw_set(MQTT_USERNAME, MQTT_PASSWORD)
    
    print(f"[{datetime.now()}] Starting MQTT log viewer...")
    print(f"[{datetime.now()}] Connecting to {MQTT_BROKER}:{MQTT_PORT}")
    
    try:
        # Connect to broker
        client.connect(MQTT_BROKER, MQTT_PORT, 60)
        
        # Start the loop
        client.loop_forever()
    except Exception as e:
        print(f"[{datetime.now()}] Error: {str(e)}")
        print(f"[{datetime.now()}] Retrying in 5 seconds...")
        time.sleep(5)
        main()

if __name__ == "__main__":
    main() 