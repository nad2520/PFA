

# Portfolio Project Proposals: Feasibility & Scalability Roadmap

**For:** 2nd Year Engineering Team
**Context:** Feasibility, Scalability, and Market Uniqueness Analysis

---

## 📂 Project 1: Collaborative Task Manager (AI-Augmented)

**Concept:** A productivity tool that evolves from a simple Kanban board into an AI-powered project assistant.
**Focus:** Productivity, Algorithms, Team Management.

### Phase 1: The MVP (The "Must-Have")

* **Goal:** Secure the passing grade with solid fundamentals.
* **Frontend (React):** Drag-and-drop columns (To Do, In Progress, Done).
* **Backend (PHP/Node):** REST API to CRUD (Create, Read, Update, Delete) tasks.
* **Indexing:** Search tasks by title or assignee using simple SQL `LIKE` queries.
* **Logic (Simple):** **The Eisenhower Matrix Automation.**
* User inputs "Deadline" and "Importance".
* System automatically tags tasks as "Do First," "Schedule," or "Delegate" based on a standard `if-then` algorithm.



### Phase 2: The Scale (The "Limit Pusher")

* **Goal:** Secure the top grade by solving the "boring data entry" problem with AI.
* **Feature A: The AI Description Maker (Generative AI)**
* *The Problem:* Users hate writing detailed bug reports.
* *The Solution:* User types a prompt: *"Fix the login bug on homepage."*
* *The Tech:* The system calls an LLM API (OpenAI/Anthropic) to generate a full technical Markdown description, acceptance criteria, and suggested tags.


* **Feature B: Natural Language Querying**
* Instead of complex filters, allow queries like: *"Show me all high-priority bugs from Sarah last week."*
* The backend converts this natural language into a SQL/NoSQL query.


* **Feature C: Intelligent Prioritization**
* Visualize "Burnout Risk" with a red graph for overloaded team members based on estimated task complexity.



### ⚠️ Senior Engineer Assessment

* **Commonality/Saturation:** **High.** (Recruiters see thousands of "To-Do" apps).
* **Uniqueness Strategy:** **Low Uniqueness** unless the AI features work perfectly.
* *Advice:* Do not highlight the "Create Task" button in your demo. Highlight the **"Generate Description"** button. That is your "Wow" factor.



---

## 📂 Project 2: "netflix" movies and series Recommendation Engine

**Concept:** A streaming architecture that understands *content scenarios* (semantic search) and *social dynamics* (cross-profile suggestions).
**Focus:** Big Data, Vector Search, User Experience.

### Phase 1: The MVP (The "Must-Have")

* **Goal:** A clean "Netflix-lite" interface to browse movies.
* **Frontend (React):** Infinite scroll grid of movie posters.
* **Backend (PHP/Python):** Serve movie data from a local MySQL database (imported from TMDB).
* **Indexing:** Filter by Year, Genre, and Rating.
* **Logic (Simple):** **Collaborative Filtering Lite.**
* "Users who liked this also liked..." (Pure SQL query finding commonalities in user favorites).



### Phase 2: The Scale (The "Limit Pusher")

* **Goal:** Move from "Metadata Matching" to "Semantic Understanding" using Vector Databases.

**The Tech Stack Update:**

* **Embeddings:** Gemini Embedding Model (Google) or a Local Model (e.g., `all-MiniLM-L6-v2`) for cost efficiency.
* **Vector Database:** **ChromaDB** (Open source, easy to run locally) to store and query the vectors.

**Feature A: "Smart Search" (Semantic Discovery)**

* *The Logic:* We embed all movie plot descriptions into vectors inside ChromaDB.
* *User Action:* User types a vague description: *"Movies about a robot that learns to love in a dystopian future."*
* *The Process:* The system converts this query into a vector using the Gemini model and queries ChromaDB for the "Nearest Neighbors" (Cosine Similarity).
* *Result:* It finds *Wall-E* or *A.I. Artificial Intelligence*, even if the word "Robot" wasn't in the title.

**Feature B: Personalized "Content-Based" Recommendation**

* *The Logic:* When a user watches a movie, we take that movie's vector description and search ChromaDB for similar vectors.
* *Result:* "Because you watched *John Wick* (Action/Revenge vector), we recommend *Nobody* (Similar Revenge vector)."
* *Hybrid Layer:* We combine this AI recommendation with standard metadata filters (simpler, no AI needed) such as **Same Genre** or **Same Actor** to refine the list.

**Feature C: Multi-Profile "Social" Weighting (Cross-Profile Logic)**

* *Architecture:* 1 Account = 4 Profiles.
* *The Algorithm:*
1. System analyzes what Profile 1 likes (using the vectors/genres/actors identified above).
2. System recommends a movie Profile 1 liked to Profile 2.
3. **Reward Function:** If Profile 2 clicks/watches  We increase the "Taste Compatibility Score" between P1 and P2.
4. **Penalty Function:** If Profile 2 ignores it  We lower the weight of Profile 1's suggestions for that specific genre.



### ⚠️ Senior Engineer Assessment

* **Commonality/Saturation:** **Medium.** (Movie apps are common, but *Vector-based* ones using ChromaDB/Gemini are cutting edge for students).
* **Uniqueness Strategy:** **Very High.**
* *Advice:* The combination of Semantic Search (Technical depth) + Cross-Profile weighting (System Design depth) makes this a portfolio-winning project.



---

## 📂 Project 3: Pet Health Guardian

**Concept:** A triage tool for pet owners to determine medical urgency using Decision Trees.
**Focus:** Logic, Medical Data, Mobile-First.

### Phase 1: The MVP (The "Must-Have")

* **Goal:** A digital medical booklet.
* **Frontend (React):** Dashboard showing pet profile, weight chart, and vaccine dates.
* **Backend (PHP):** Store vet visits and send email reminders (PHP Mailer) before vaccines.
* **Logic (Simple):** **Rule-Based Alerts.**
* `If Weight drops by >10% in 1 month -> Trigger "Health Alert".`



### Phase 2: The Scale (The "Limit Pusher")

* **Goal:** The "Symptom Checker" (Triage AI).
* **Feature A: The Decision Tree Classifier**
* *The Model:* Use a **Decision Tree** (Machine Learning) to predict urgency: "Go to Vet Immediately" vs "Monitor for 24 hours".
* *Why:* Decision trees are interpretable. You can show the jury exactly *why* the AI gave that advice.


* **Feature B: Handling Data Scarcity (The 2 Scenarios)**
* *Scenario 1 (You have data):* Train a model on a dataset mapping symptoms (Vomiting, Lethargy) to outcomes.
* *Scenario 2 (No data):*
1. **Expert System:** Hard-code logic based on vet journals (`IF gums == pale AND breathing == rapid THEN urgency = HIGH`).
2. **Synthetic Data:** Use GPT-4 to generate 5,000 realistic "fake" cases of dog symptoms, then train your model on that synthetic data (Must disclose this in presentation).





### ⚠️ Senior Engineer Assessment

* **Commonality/Saturation:** **Low.** (Very few junior devs build VetTech).
* **Uniqueness Strategy:** **High (Niche Appeal).**
* *Advice:* This has high emotional value ("Saving animals"). However, it is risky if you cannot demonstrate the "Medical AI" works reliably.



---

## 📊 The Final Decision Matrix

I have merged the previous feasibility scores with the new **"Uniqueness/Saturation"** rating to give you a definitive mathematical answer.

| Criteria | **Project 1: Task Manager** | **Project 2: Netflix** | **Project 3: Pet Health** |
| --- | --- | --- | --- |
| **Data Availability** | 2/10 (Must create manually) | **10/10** (TMDB is instant) | 4/10 (Hard to find records) |
| **Visual "Wow" Factor** | 5/10 (Looks like Excel) | **9/10** (Posters/Trailers) | 7/10 (Cute animals) |
| **Tech Stack Alignment** | 10/10 (Standard CRUD) | 9/10 (Perfect for Search) | 8/10 (Standard CRUD) |
| **Uniqueness (Saturation)** | **Low** (Generic Market) | **High** (Vector Tech) | **Medium-High** (Niche) |
| **AI Feasibility** | 7/10 (LLM integration) | **9/10** (RecSys is standard) | 4/10 (Medical AI is risky) |
| **Complexity to Scale** | Hard (Needs user data) | **Easy** (Data exists) | Hard (Needs medical logic) |
| **TOTAL SCORE** | **28 / 60** | **47 / 60** | **33 / 60** |

---

## 🏆 Final Verdict

### 🥇 Winner: Project 2 (Netflix)

**Why:** It hits the "Sweet Spot."

1. **Data:** You can download the data tonight. You don't need to type dummy data.
2. **Tech:** You get to use **ChromaDB** and **Gemini Embeddings**, which are top-tier CV keywords right now.
3. **Scaling:** The "Netflix Logic" (Profile 1 recommends to Profile 2) + Semantic Search is a brilliant way to show you understand complex system design.

### 🥈 Runner Up: Project 3 (Pet Health)

**Why:** It has heart.
If you are passionate about animals, do this. But be warned: **Scenario B (Data Scarcity)** is very real. You will likely spend more time generating fake data than coding the app.

### 🥉 Last Place: Project 1 (Task Manager)

**Why:** It is too risky to be boring.
Unless your **AI Description Maker** is absolutely mind-blowing, the jury will look at it and say, "Oh, another Trello clone." It requires the most effort to make it look unique.
