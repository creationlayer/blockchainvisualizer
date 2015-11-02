from ws4py.client.threadedclient import WebSocketClient
from string import whitespace
from subprocess import call
import threading

class BlockchainListener(WebSocketClient):
    def opened(self):
        print "Connection opened."
        self.send("{\"op\":\"blocks_sub\"}")
        self.send("{\"op\":\"unconfirmed_sub\"}")

    def closed(self, code, reason=None):
        print "Closed down", code, reason

    def received_message(self, m):
        m = str(m)
        m = m.translate( None, whitespace)
        call(["php", "artisan", "send:blockchain-data", m])

    def keep_open(self):
        threading.Timer(30.0, self.keep_open).start()
        self.send("ping")


if __name__ == '__main__':
    try:
        ws = BlockchainListener('wss://ws.blockchain.info/inv', protocols=['http-only', 'chat'])
        ws.connect()
        ws.keep_open()
        ws.run_forever()
    except KeyboardInterrupt:
        ws.close()