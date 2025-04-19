# 🧠 Twitter AI Integration & Chatbot

This project was built during the **AI24 Hackathon** by **SkyTech** team. The project integrates Twitter API to fetch and display tweets, while leveraging OpenAI's API for creating a chatbot that generates responses, tweets, and even images to be shared on Twitter.

## 🚀 Overview

The project aims to enhance the interaction with Twitter data by:
- Displaying tweets and user stats.
- Creating a **Chatbot** using OpenAI's API to automatically generate engaging responses, tweets, and images.
- Providing automated content generation for Twitter posts, which can be further shared through the integrated Twitter API.

## 🧰 Technologies Used

- 🐦 **Twitter API** (for retrieving and displaying tweets)
- 🤖 **OpenAI API** (for generating text responses and images)
- 🖥️ **PHP** (used for the backend integration)
- 💻 **Python** (for FastAPI)
- 🖼️ **OpenAI DALL·E API** (for image generation)
- 🌐 **HTML/CSS/JavaScript** (for web interface, if applicable)

## 💡 Features

- **Twitter Integration**: Fetch and display tweets based on keywords or hashtags.
- **AI-Powered Chatbot**: A chatbot that uses OpenAI's API to generate intelligent responses to users, along with the ability to generate text and images for sharing on Twitter.
- **Automated Content Creation**: Generate Twitter posts automatically through the Chatbot.
- **Image Generation**: Automatically generate images with the OpenAI DALL·E API for enhanced social media engagement.

## 📸 Screenshots



## ⚙️ How to Run

1. Clone the repository:
   `git clone https://github.com/Skylok-24/twitter-ai-chatbot.git`

2. Install the required dependencies:
   - For **PHP backend**:  
     `composer install` (if Composer is used for dependency management)
   - For **FastAPI**:  
     `pip install -r requirements.txt`

3. Set up **Twitter API** and **OpenAI API** credentials:
   - Create a Twitter Developer account and get your API keys from [Twitter Developer Portal](https://developer.twitter.com/).
   - Get your API keys from [OpenAI](https://beta.openai.com/signup/).

4. Configure the credentials in the `config` file or within the script.

5. Run the project:
   - Start the **PHP server** (if you're using a PHP backend).
   - For **FastAPI**, run:
     `uvicorn main:app --reload`

6. Visit the web interface or test the backend endpoints.

## 🏆 Hackathon Info

This project was developed and presented by **SkyTech** during the **AI24 Hackathon** in [2025], and it showcases the integration of AI with social media platforms for content automation and real-time interactions.

## 📜 License

MIT License

---

Made with ❤️ by **Lokman Abdelmoname Brahmia** and the **SkyTech Team**
