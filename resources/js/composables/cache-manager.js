export class CacheManager {
    constructor(ttl = 5 * 60 * 1000) { // 5 minutos por defecto
        this.cache = new Map();
        this.ttl = ttl;
        this.queue = [];
        this.processing = false;
        this.requestDelay = 1000; // 1 segundo entre peticiones
    }

    async get(key, fetchFunction) {
        if (this.cache.has(key)) {
            const { value, timestamp } = this.cache.get(key);
            if (Date.now() - timestamp < this.ttl) {
                return value;
            }
            this.cache.delete(key);
        }

        return this.enqueueRequest(key, fetchFunction);
    }

    async enqueueRequest(key, fetchFunction) {
        return new Promise((resolve, reject) => {
            this.queue.push({
                key,
                fetchFunction,
                resolve,
                reject
            });

            if (!this.processing) {
                this.processQueue();
            }
        });
    }

    async processQueue() {
        if (this.queue.length === 0) {
            this.processing = false;
            return;
        }

        this.processing = true;
        const { key, fetchFunction, resolve, reject } = this.queue.shift();

        try {
            const value = await fetchFunction();
            this.cache.set(key, {
                value,
                timestamp: Date.now()
            });
            resolve(value);
        } catch (error) {
            reject(error);
        }

        // Esperar antes de la siguiente petición
        await new Promise(resolve => setTimeout(resolve, this.requestDelay));
        this.processQueue();
    }
}
