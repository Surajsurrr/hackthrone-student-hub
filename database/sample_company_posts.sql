-- Sample company posts for testing

-- Assuming company_id = 1 exists (you'll need to adjust this based on your actual company IDs)

INSERT INTO company_posts (company_id, title, content, post_type, tags, status, published_at) VALUES
(1, 'Breakthrough in AI Research: New Language Model', 
'We are excited to announce our latest breakthrough in artificial intelligence research. Our team has successfully developed a new language model that demonstrates unprecedented understanding of context and nuance. This achievement represents years of dedicated research and collaboration across our AI labs worldwide.

Key highlights:
- 40% improvement in natural language understanding
- Enhanced multilingual capabilities covering 100+ languages
- Reduced computational requirements by 60%
- Open-source framework for academic research

This innovation will pave the way for more accessible and powerful AI applications across various industries.', 
'research', 
'AI, Machine Learning, Innovation, Research', 
'published', 
NOW()),

(1, 'Our Journey: 20 Years of Innovation',
'As we celebrate our 20th anniversary, we take a moment to reflect on our remarkable journey from a small startup to a global technology leader.

Our Story:
2004: Founded in a garage with a vision to transform technology
2008: Launched our first major product, reaching 1 million users
2012: Expanded internationally to 15 countries
2016: Achieved unicorn status with $1B valuation
2020: Went public, raising $500M in IPO
2024: Serving 100M+ users globally

Throughout this journey, our commitment to innovation and customer satisfaction has remained unwavering. Thank you to our incredible team, partners, and customers who made this possible.',
'history',
'Milestone, Anniversary, Company History',
'published',
NOW()),

(1, 'New Product Launch: CloudSync Pro',
'We are thrilled to announce the launch of CloudSync Pro, our next-generation cloud storage and collaboration platform.

Features:
✅ Unlimited storage for enterprise clients
✅ End-to-end encryption for maximum security
✅ Real-time collaboration tools
✅ AI-powered file organization
✅ Integration with 50+ business applications

Special Launch Offer: 50% off for the first 6 months!

Available now at www.cloudsync.com',
'announcement',
'Product Launch, Cloud, Technology',
'published',
NOW()),

(1, 'Life at TechCorp: Our Culture of Innovation',
'At TechCorp, we believe that great products come from great people working in an inspiring environment. Our culture is built on five core pillars:

🌟 Innovation First: Encouraging creative thinking and risk-taking
🤝 Collaboration: Cross-functional teams working together
📚 Continuous Learning: $5,000 annual learning budget per employee
🌍 Diversity & Inclusion: 50% diverse workforce across all levels
⚖️ Work-Life Balance: Flexible hours and unlimited PTO

Join us and be part of something extraordinary!',
'culture',
'Culture, Workplace, Careers',
'published',
NOW()),

(1, 'TechCorp Wins "Best Workplace Innovation Award 2024"',
'We are honored to receive the prestigious "Best Workplace Innovation Award 2024" from the Global Tech Industry Association!

This award recognizes our commitment to:
- Creating an inclusive and supportive work environment
- Implementing cutting-edge workplace technologies
- Prioritizing employee wellbeing and professional growth
- Leading sustainable business practices

This achievement belongs to our amazing team of 10,000+ employees worldwide. Thank you for making TechCorp an exceptional place to work!',
'achievement',
'Award, Recognition, Achievement',
'published',
NOW()),

(1, 'Q4 2024 Company Update',
'Dear Team and Stakeholders,

As we conclude Q4 2024, I am pleased to share some exciting updates:

📈 Financial Performance:
- Revenue growth: 45% YoY
- New customers: 50,000+
- Market expansion: Entered 12 new markets

🚀 Product Updates:
- 3 major product launches
- 100+ new features released
- 99.9% platform uptime

👥 Team Growth:
- 2,000+ new employees hired
- 15 new office locations
- Enhanced benefits package

Looking ahead to 2025, we remain committed to innovation and excellence.

Best regards,
CEO, TechCorp',
'general',
'Update, Company News, Quarterly Report',
'published',
NOW());
