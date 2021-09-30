import { defineConfig } from "laravel-vite";
import vue from "@vitejs/plugin-vue";
export default defineConfig({
	    server: {
		    hmr: {
			    host: 'localhost',
			    protocol: 'ws'
		    }
	    }
    })
    .withPlugin(vue);
