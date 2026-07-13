<x-filament-panels::page>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
            <div class="p-5 bg-gray-50 rounded-xl col-span-2 border border-gray-200">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Current Stage Information</h3>
                <div id="stage-info" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="font-semibold text-gray-600">Current Stage:</div>
                        <div id="current-stage" class="text-gray-800">Fingerling</div>
                        
                        <div class="font-semibold text-gray-600">Feed type:</div>
                        <div id="current-feed-type" class="text-gray-800">-</div>
                        
                        <div class="font-semibold text-gray-600">Feeding frequency:</div>
                        <div id="current-frequency" class="text-gray-800">-</div>
                        
                        <div class="font-semibold text-gray-600">Protein content:</div>
                        <div id="current-protein" class="text-gray-800">-</div>
                        
                        <div class="font-semibold text-gray-600">Amount:</div>
                        <div id="current-amount" class="text-gray-800">-</div>
                    </div>
                </div>
            </div>

            <div class="col-span-3 p-5 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Growth Stages</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#4CAF50]"></div>
                        <span class="text-sm font-medium">Fingerling</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#2196F3]"></div>
                        <span class="text-sm font-medium">Juvenile</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#9C27B0]"></div>
                        <span class="text-sm font-medium">Sub-Adult</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#F44336]"></div>
                        <span class="text-sm font-medium">Adult</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div id="calendar" class="bg-white rounded-xl shadow-md overflow-hidden"></div>
    </div>
</x-filament-panels::page>

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6/main.min.css' rel='stylesheet'>
    <style>
        .fc {
            font-family: inherit;
        }

        .fc .fc-toolbar {
            background-color: #1a202c;
            padding: 1.25rem;
            color: white;
            margin-bottom: 0 !important;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600;
        }

        .fc .fc-button {
            background-color: rgba(255,255,255,0.1) !important;
            border: none !important;
            color: white !important;
            transition: background-color 0.2s;
        }

        .fc .fc-button:hover {
            background-color: rgba(255,255,255,0.2) !important;
        }

        .fc .fc-button-active {
            background-color: rgba(255,255,255,0.3) !important;
        }

        .fc-day-today {
            background-color: rgba(66, 165, 245, 0.1) !important;
        }

        .fc-event {
            border: none !important;
            padding: 3px 6px !important;
            font-size: 0.875rem !important;
            border-radius: 4px !important;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .fc-daygrid-event {
            white-space: normal !important;
            align-items: center !important;
            margin: 2px 0 !important;
        }

        .fc-event-title,
        .fc-event-time {
            color: white !important;
            font-weight: 500;
        }

        .fc-daygrid-event-dot {
            display: none;
        }

        .fc-event[data-stage="Fingerling"] { background-color: #4CAF50 !important; }
        .fc-event[data-stage="Juvenile"] { background-color: #2196F3 !important; }
        .fc-event[data-stage="Sub-Adult"] { background-color: #9C27B0 !important; }
        .fc-event[data-stage="Adult"] { background-color: #F44336 !important; }

        .fc-event.locked {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const events = @json($this->getEvents());
            
            let currentStage = 'Fingerling'; // Set the initial stage

            function updateStageInfo(event) {
                const props = event.extendedProps;
                document.getElementById('current-stage').textContent = props.stage;
                document.getElementById('current-feed-type').textContent = props.feed_name;
                document.getElementById('current-frequency').textContent = 
                    `${props.feeding_frequency || 4} times a day`;
                document.getElementById('current-protein').textContent = 
                    `${props.protein_content || '30-35'}%`;
                document.getElementById('current-amount').textContent = 
                    `${props.fish_amount || '50'} grams per session`;
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events.map(event => ({
                    ...event,
                    backgroundColor: getEventColor(event.extendedProps.stage),
                    textColor: 'white',
                    borderColor: 'transparent',
                    className: event.extendedProps.stage !== currentStage ? 'locked' : ''
                })),
                eventClick: function(info) {
                    if (info.event.extendedProps.stage !== currentStage) {
                        Swal.fire({
                            title: 'Stage Locked',
                            text: 'This growth stage is not yet unlocked.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    updateStageInfo(info.event);
                    
                    Swal.fire({
                        title: 'Feeding Schedule Details',
                        html: `
                            <div class="text-left">
                                <p class="mb-2"><strong>Stage:</strong> ${info.event.extendedProps.stage}</p>
                                <p class="mb-2"><strong>Feed Type:</strong> ${info.event.extendedProps.feed_name}</p>
                                <p class="mb-2"><strong>Time:</strong> ${info.event.start.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                                <p class="mb-2"><strong>Amount:</strong> ${info.event.extendedProps.fish_amount}g</p>
                                <p class="mb-2"><strong>Protein Content:</strong> ${info.event.extendedProps.protein_content}%</p>
                                <p class="mb-2"><strong>Feeding Frequency:</strong> ${info.event.extendedProps.feeding_frequency}x daily</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#3085d6'
                    });
                },
                eventContent: function(arg) {
                    return {
                        html: `
                            <div class="text-xs p-1">
                                <div class="font-medium">${arg.event.title}</div>
                                <div>${arg.timeText}</div>
                            </div>
                        `
                    };
                },
                dayMaxEvents: true
            });

            calendar.render();

            // Set initial stage info if there are events
            if (events.length > 0) {
                const initialEvent = events.find(event => event.extendedProps.stage === currentStage);
                if (initialEvent) {
                    updateStageInfo({ extendedProps: initialEvent.extendedProps });
                }
            }

            function getEventColor(stage) {
                const colors = {
                    'Fingerling': '#4CAF50',
                    'Juvenile': '#2196F3',
                    'Sub-Adult': '#9C27B0',
                    'Adult': '#F44336'
                };
                return colors[stage] || '#9E9E9E';
            }

            // Function to unlock next stage (this is just a placeholder, you'll need to implement the actual logic)
            function unlockNextStage() {
                const stages = ['Fingerling', 'Juvenile', 'Sub-Adult', 'Adult'];
                const currentIndex = stages.indexOf(currentStage);
                if (currentIndex < stages.length - 1) {
                    currentStage = stages[currentIndex + 1];
                    calendar.getEvents().forEach(event => {
                        if (event.extendedProps.stage === currentStage) {
                            event.remove();
                            calendar.addEvent({
                                ...event.toPlainObject(),
                                className: ''
                            });
                        }
                    });
                    calendar.render();
                    Swal.fire({
                        title: 'Stage Unlocked',
                        text: `You've unlocked the ${currentStage} stage!`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            }

            // Add a button to simulate unlocking the next stage (you can remove this in the final implementation)
            const unlockButton = document.createElement('button');
            unlockButton.textContent = 'Unlock Next Stage';
            unlockButton.className = 'px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-4';
            unlockButton.onclick = unlockNextStage;
            calendarEl.parentNode.insertBefore(unlockButton, calendarEl.nextSibling);
        });
    </script>
@endpush




{{-- <x-filament-panels::page>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
            <div class="p-5 bg-gray-50 rounded-xl col-span-2 border border-gray-200">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Current Stage Information</h3>
                <div id="stage-info" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="font-semibold text-gray-600">Feed type:</div>
                        <div id="current-feed-type" class="text-gray-800">-</div>
                        
                        <div class="font-semibold text-gray-600">Feeding frequency:</div>
                        <div id="current-frequency" class="text-gray-800">-</div>
                        
                        <div class="font-semibold text-gray-600">Protein content:</div>
                        <div id="current-protein" class="text-gray-800">-</div>
                        
                        <div class="font-semibold text-gray-600">Amount:</div>
                        <div id="current-amount" class="text-gray-800">-</div>
                    </div>
                </div>
            </div>

            <div class="col-span-3 p-5 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Growth Stages</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#4CAF50]"></div>
                        <span class="text-sm font-medium">Fingerling</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#2196F3]"></div>
                        <span class="text-sm font-medium">Juvenile</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#9C27B0]"></div>
                        <span class="text-sm font-medium">Sub-Adult</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm">
                        <div class="w-5 h-5 rounded-full bg-[#F44336]"></div>
                        <span class="text-sm font-medium">Adult</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div id="calendar" class="bg-white rounded-xl shadow-md overflow-hidden"></div>
    </div>
</x-filament-panels::page>

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6/main.min.css' rel='stylesheet'>
    <style>
        .fc {
            font-family: inherit;
        }

        .fc .fc-toolbar {
            background-color: #1a202c;
            padding: 1.25rem;
            color: white;
            margin-bottom: 0 !important;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600;
        }

        .fc .fc-button {
            background-color: rgba(255,255,255,0.1) !important;
            border: none !important;
            color: white !important;
            transition: background-color 0.2s;
        }

        .fc .fc-button:hover {
            background-color: rgba(255,255,255,0.2) !important;
        }

        .fc .fc-button-active {
            background-color: rgba(255,255,255,0.3) !important;
        }

        .fc-day-today {
            background-color: rgba(66, 165, 245, 0.1) !important;
        }

        .fc-event {
            border: none !important;
            padding: 3px 6px !important;
            font-size: 0.875rem !important;
            border-radius: 4px !important;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .fc-daygrid-event {
            white-space: normal !important;
            align-items: center !important;
            margin: 2px 0 !important;
        }

        .fc-event-title,
        .fc-event-time {
            color: white !important;
            font-weight: 500;
        }

        .fc-daygrid-event-dot {
            display: none;
        }

        .fc-event[data-stage="Fingerling"] { background-color: #4CAF50 !important; }
        .fc-event[data-stage="Juvenile"] { background-color: #2196F3 !important; }
        .fc-event[data-stage="Sub-Adult"] { background-color: #9C27B0 !important; }
        .fc-event[data-stage="Adult"] { background-color: #F44336 !important; }
    </style>
@endpush

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const events = @json($this->getEvents());
            
            function updateStageInfo(event) {
                const props = event.extendedProps;
                document.getElementById('current-feed-type').textContent = props.feed_name;
                document.getElementById('current-frequency').textContent = 
                    `${props.feeding_frequency || 4} times a day`;
                document.getElementById('current-protein').textContent = 
                    `${props.protein_content || '30-35'}%`;
                document.getElementById('current-amount').textContent = 
                    `${props.fish_amount || '50'} grams per session`;
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events.map(event => ({
                    ...event,
                    backgroundColor: getEventColor(event.extendedProps.stage),
                    textColor: 'white',
                    borderColor: 'transparent'
                })),
                eventClick: function(info) {
                    updateStageInfo(info.event);
                    
                    Swal.fire({
                        title: 'Feeding Schedule Details',
                        html: `
                            <div class="text-left">
                                <p class="mb-2"><strong>Stage:</strong> ${info.event.extendedProps.stage}</p>
                                <p class="mb-2"><strong>Feed Type:</strong> ${info.event.extendedProps.feed_name}</p>
                                <p class="mb-2"><strong>Time:</strong> ${info.event.start.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                                <p class="mb-2"><strong>Amount:</strong> ${info.event.extendedProps.fish_amount}g</p>
                                <p class="mb-2"><strong>Protein Content:</strong> ${info.event.extendedProps.protein_content}%</p>
                                <p class="mb-2"><strong>Feeding Frequency:</strong> ${info.event.extendedProps.feeding_frequency}x daily</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#3085d6'
                    });
                },
                eventContent: function(arg) {
                    return {
                        html: `
                            <div class="text-xs p-1">
                                <div class="font-medium">${arg.event.title}</div>
                                <div>${arg.timeText}</div>
                            </div>
                        `
                    };
                },
                dayMaxEvents: true
            });

            calendar.render();

            // Set initial stage info if there are events
            if (events.length > 0) {
                updateStageInfo({ extendedProps: events[0].extendedProps });
            }

            function getEventColor(stage) {
                const colors = {
                    'Fingerling': '#4CAF50',
                    'Juvenile': '#2196F3',
                    'Sub-Adult': '#9C27B0',
                    'Adult': '#F44336'
                };
                return colors[stage] || '#9E9E9E';
            }
        });
    </script>
@endpush --}}