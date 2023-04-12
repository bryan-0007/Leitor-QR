###################################################

#           P17 ----> Relay_Ch1

##################################################
#!/usr/bin/python
# -*- coding:utf-8 -*-
import RPi.GPIO as GPIO
import time

Relay_Ch1 = 17

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

GPIO.setup(Relay_Ch1,GPIO.OUT)

print("Setup The Relay Module is [success]")

def teste():
	try:
		#Control the Channel 1
		GPIO.output(Relay_Ch1,GPIO.HIGH)
		print("Channel 1:The Common Contact is access to the Normal Open Contact!")
		time.sleep(1)

		GPIO.output(Relay_Ch1,GPIO.LOW)
		print("Channel 1:The Common Contact is access to the Normal Closed Contact!\n")
		time.sleep(1)
	
	except:
		print("except")
		GPIO.cleanup()

teste()