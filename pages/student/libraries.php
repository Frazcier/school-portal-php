<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/libraries.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Libraries</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Content Library</h1>
                    <h3>Access course materials, e-books, and research papers.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-bookmark"></i> Saved Items
                    </button>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search for title, author, or topic...">
                </div>
                
                <div class="filter-group">
                    <div class="select-wrapper">
                        <select>
                            <option selected>All Subjects</option>
                            <option>IT 114 (Prog 2)</option>
                            <option>IT 115 (HCI)</option>
                            <option>IT 116 (Web)</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    <div class="select-wrapper">
                        <select>
                            <option selected>Recent</option>
                            <option>Oldest</option>
                            <option>A-Z</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                </div>
            </div>

            <div class="library-container">
                <input type="radio" id="tab-materials" name="lib-tabs" checked hidden>
                <input type="radio" id="tab-ebooks" name="lib-tabs" hidden>
                <input type="radio" id="tab-research" name="lib-tabs" hidden>

                <div class="tabs-nav">
                    <label for="tab-materials" class="tab-link">Course Materials</label>
                    <label for="tab-ebooks" class="tab-link">E-Books</label>
                    <label for="tab-research" class="tab-link">Research Papers</label>
                </div>

                <div class="tab-content">
                    
                    <div id="view-materials" class="resources-grid fadeIn">
                        
                        <div class="resource-card">
                            <div class="card-icon pdf">
                                <img src="../../assets/img/icons/file-icon.svg" alt="PDF">
                            </div>
                            <div class="card-body">
                                <span class="tag">IT 114</span>
                                <h4>Advanced OOP Concepts</h4>
                                <p>Encapsulation, inheritance, polymorphism, and abstraction.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">PDF &bullet; 2.4 MB</span>
                                <button class="icon-btn"><i class="fas fa-download"></i></button>
                            </div>
                        </div>

                        <div class="resource-card">
                            <div class="card-icon slides">
                                <img src="../../assets/img/icons/course-materials-2-icon.svg" alt="Slides">
                            </div>
                            <div class="card-body">
                                <span class="tag">IT 115</span>
                                <h4>HCI Fundamentals</h4>
                                <p>Usability principles, heuristic evaluation, and cognitive psychology.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">PPTX &bullet; 5.1 MB</span>
                                <button class="icon-btn"><i class="fas fa-download"></i></button>
                            </div>
                        </div>

                        <div class="resource-card">
                            <div class="card-icon link">
                                <img src="../../assets/img/icons/visit-link-icon.svg" alt="Link">
                            </div>
                            <div class="card-body">
                                <span class="tag">IT 116</span>
                                <h4>Modern Web Apps</h4>
                                <p>Guide to front-end development with HTML, CSS, and JS.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">External Link</span>
                                <button class="icon-btn"><i class="fas fa-external-link-alt"></i></button>
                            </div>
                        </div>

                        <div class="resource-card">
                            <div class="card-icon pdf">
                                <img src="../../assets/img/icons/file-icon.svg" alt="PDF">
                            </div>
                            <div class="card-body">
                                <span class="tag">STATS 22</span>
                                <h4>Data Analysis w/ Python</h4>
                                <p>Introduction to statistical computing and data preprocessing.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">PDF &bullet; 1.8 MB</span>
                                <button class="icon-btn"><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                    </div>

                    <div id="view-ebooks" class="resources-grid fadeIn">
                        <div class="resource-card">
                            <div class="card-icon ebook">
                                <img src="../../assets/img/icons/e-books-1-icon.svg" alt="Book">
                            </div>
                            <div class="card-body">
                                <span class="tag">Security</span>
                                <h4>Cybersecurity Essentials</h4>
                                <p>Protecting your digital world: Threat detection and encryption.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">EPUB &bullet; 12 MB</span>
                                <button class="icon-btn"><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                        <div class="resource-card">
                            <div class="card-icon ebook">
                                <img src="../../assets/img/icons/e-books-3-icon.svg" alt="Book">
                            </div>
                            <div class="card-body">
                                <span class="tag">Cloud</span>
                                <h4>Cloud Computing</h4>
                                <p>A practical approach for businesses and developers.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">PDF &bullet; 8 MB</span>
                                <button class="icon-btn"><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                    </div>

                    <div id="view-research" class="resources-grid fadeIn">
                        <div class="resource-card">
                            <div class="card-icon research">
                                <img src="../../assets/img/icons/research-papers-icon.svg" alt="Paper">
                            </div>
                            <div class="card-body">
                                <span class="tag">AI</span>
                                <h4>AI in Cybersecurity</h4>
                                <p>Enhancing threat detection and response using ML algorithms.</p>
                            </div>
                            <div class="card-footer">
                                <span class="file-info">PDF &bullet; 2024</span>
                                <button class="icon-btn"><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="../../assets/js/component-student.js"></script>
</body>
</html>