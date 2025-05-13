from fastapi import FastAPI, HTTPException, Request
from pydantic import BaseModel, Field
from typing import List, Optional
from datetime import datetime
from langchain_google_genai import ChatGoogleGenerativeAI
import json

app = FastAPI(title="AI Processing API", 
              description="API for user intent analysis, tweet analysis, and chatbot functionality")


llm = ChatGoogleGenerativeAI(
    temperature=0.7,
    model="gemini-2.0-flash",
    google_api_key="AIzaSyCdX_T5DfcM6pMADeOQONt_R6VBFcBH7zk" 
)


class UserIntentRequest(BaseModel):
    query: str = Field(..., description="User query to analyze")
    user_id: Optional[str] = Field(None, description="Optional user identifier")
    context: Optional[str] = Field(None, description="Additional context")

class TweetAnalysisRequest(BaseModel):
    product_name: str = Field(..., description="Name of the product")
    product_describtion: str = Field(..., description="Description of the product") 
    created_at: Optional[str] = Field(None, description="Creation timestamp")
    reactions: List[str] = Field(..., description="List of reactions to the product")

class ChatbotRequest(BaseModel):
    question: str = Field(..., description="User's question")


@app.post("/userintentanalysis/")
async def user_intent_analysis(request: UserIntentRequest):
    try:
        prompt = (
            f"Analyze the user's intent for the following query in **no more than 30 words**:\n"
            f"Query: {request.query}\n"
            f"Context: {request.context if request.context else 'None provided'}\n"
            f"Identify the primary intent, any secondary intents, and confidence level."
        )
        
        response = llm.invoke(prompt)
        
        return {
            "status": "success",
            "intent_analysis": response.content,
            "timestamp": datetime.now().isoformat()
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Intent analysis failed: {str(e)}")

@app.post("/tweetanalisisagent/")
async def tweet_analysis_agent(request: TweetAnalysisRequest):
    try:
       
        created_at = request.created_at if request.created_at else datetime.now().isoformat()
        
        prompt = (
            f"Answer the following question in **no more than 60 words**:\n"
            f"product_name: {request.product_name}\n"
            f"created_at: {created_at}\n"
            f"product_describtion: {request.product_describtion}\n"
            f"reactions: {', '.join(request.reactions)}\n"
            f"Please describe the reaction to the product and consider any tips to improve it.\n"
        )
        
        response = llm.invoke(prompt)
        
        return {
            "status": "success",
            "analysis": response.content,
            "sentiment_summary": summarize_sentiment(request.reactions),
            "timestamp": datetime.now().isoformat()
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Tweet analysis failed: {str(e)}")

@app.post("/chatbotagent/")
async def chatbot_agent(request: ChatbotRequest):
    try:
        prompt = (
            f"Answer the following question in **no more than 100 words**:\n"
            f"Make the response professional with appropriate terminology while keeping it clear and helpful:\n"   
            f"Question: {request.question}"
        )
        
        response = llm.invoke(prompt)
        
        return {
            "status": "success",
            "answer": response.content,
            "timestamp": datetime.now().isoformat()
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Chatbot response failed: {str(e)}")


def summarize_sentiment(reactions: List[str]) -> str:
    positive_indicators = ["good", "great", "amazing", "love", "excellent", "nice", "perfect", "like"]
    negative_indicators = ["bad", "poor", "terrible", "hate", "dislike", "awful", "horrible"]
    
    positive_count = sum(1 for r in reactions if any(pi in r.lower() for pi in positive_indicators))
    negative_count = sum(1 for r in reactions if any(ni in r.lower() for ni in negative_indicators))
    
    if positive_count > negative_count * 2:
        return "very positive"
    elif positive_count > negative_count:
        return "somewhat positive"
    elif negative_count > positive_count * 2:
        return "very negative"
    elif negative_count > positive_count:
        return "somewhat negative"
    else:
        return "neutral or mixed"


@app.exception_handler(Exception)
async def global_exception_handler(request: Request, exc: Exception):
    return {
        "status": "error",
        "message": str(exc),
        "timestamp": datetime.now().isoformat()
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)