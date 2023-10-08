#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
FROM node:14

# Set working directory
WORKDIR /app

# Copy package.json and package-lock.json to the working directory
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code to the working directory
COPY . .

# Expose the port that Vue.js development server will run on
EXPOSE 8080

# Start the Vue.js development server
CMD ["npm", "run", "dev"]