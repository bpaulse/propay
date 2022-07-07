let opponents = [
	'Manchester',
	'City',
	'Liverpool',
	'Everton',
	'Newcastle',
	'Nottingham',
	'Brighton',
	'Sheffield',
	'Birmingham',
	'Bristol',
	'Sunderland',
	'Leeds',
	'Chelsea',
	'Derby',
	'Tottenham',
	'Arsenal',
	'West Ham',
	'Bournemouth',
	'Stoke',
	'Wolves'
]

opponents = shuffle(opponents)

let roundCount = (opponents.length - 1)
let matchesPerRoundCount = opponents.length / 2;
let alternate = false;
let offsetArray = [];

// generate the rounds of matches
var firstHalfSeasonFixtures = generateFixtures(0)
console.log('first', firstHalfSeasonFixtures[0])
var secondHalfSeasonFixtures = generateFixtures(opponents.length - 1)
console.log('second', secondHalfSeasonFixtures[0])
var allFixtures = firstHalfSeasonFixtures.concat(secondHalfSeasonFixtures)

// fixture lists
outputFixtures(allFixtures)
outputFixtureListForEachOpponent(allFixtures)





// ------------------------------------------

function generateFixtures(roundNoOffset) {
	var fixtures = [];
		offsetArray = generateOffsetArray(opponents.length);
	
	for (let roundNo = 1; roundNo <= roundCount; roundNo++) {
		alternate = !alternate;

		let homes = getHomes(roundNo)
		let aways = getAways(roundNo) 

		for (let matchIndex = 0; matchIndex < matchesPerRoundCount; matchIndex++) {
			if (alternate === true) {
				fixtures.push({
					roundNo: roundNo  + roundNoOffset,
					matchNo: matchIndex,
					homeOpponentId: homes[matchIndex],
					awayOpponentId: aways[matchIndex],
					homeOpponent: opponents[homes[matchIndex]],
					awayOpponent: opponents[aways[matchIndex]]
				})            
			} else {
				fixtures.push({
					roundNo: roundNo + roundNoOffset,
					matchNo: matchIndex,
					homeOpponentId: aways[matchIndex],
					awayOpponentId: homes[matchIndex],
					homeOpponent: opponents[aways[matchIndex]],
					awayOpponent: opponents[homes[matchIndex]]
				})
			}
			
			if (homes[matchIndex] == aways[matchIndex]) {
				console.error('Teams cannot play themselves')
			}
		}
	}
	
	return fixtures
}

function outputFixtures(fixtures) {
	let roundNo = 0;
		for (let fixtureId = 0; fixtureId < fixtures.length; fixtureId++) {
		let fixture = fixtures[fixtureId]
		
		if (fixture.roundNo > roundNo) {
			writeToPage("<strong>Round " + fixture.roundNo + "</strong>")
			roundNo = fixture.roundNo
		}
			
		writeToPage(fixture.homeOpponent + " vs " + fixture.awayOpponent)
	}
}

function outputFixtureListForEachOpponent(fixtures) {
	// loop the opponents, to display their fixtures
	for (let opponentId = 0; opponentId < opponents.length; opponentId++) {
		writeToPage('<strong>Fixture list for ' + opponents[opponentId] + '</strong>')
		
		// find all the fixtures involving this opponent
		let fixtureList = _.filter(fixtures, (fixture) => { 
		  	return (
					fixture.homeOpponentId === opponentId 
				|| fixture.awayOpponentId === opponentId
			)
		});
		
		// output the fixtures for this opponent 
		for (var fixtureId in fixtureList) {
			let fixture = fixtureList[fixtureId]
			if (opponentId === fixture.homeOpponentId) {
				writeToPage('H ' + fixture.awayOpponent)
			} else {
				writeToPage('A ' + fixture.homeOpponent)
			}
		}
	}
}

function generateOffsetArray() {
	// generate an array of all indexes, repeated so it si long enough
		for (let i = 1; i < opponents.length; i++) {        
		offsetArray.push(i)
	}
	
	offsetArray = offsetArray.concat(offsetArray)
	
	console.log('the offset array: ', offsetArray)
	
	return offsetArray
}

function getHomes(roundNo) {
	let offset = opponents.length - roundNo
	let homes = offsetArray.slice(offset, (offset + matchesPerRoundCount - 1))
	
	// for round robin, the 0 opponent is fixed, so homes always start with a 0
	return [0, ...homes]
}

function getAways(roundNo) {
	let offset = (opponents.length - roundNo) + (matchesPerRoundCount - 1)
	let aways = offsetArray.slice(offset, (offset + matchesPerRoundCount))
	
	return aways.reverse()
}

function writeToPage(text) {
	var div = document.createElement('div')
	div.innerHTML = text
	document.body.appendChild(div)
}

function shuffle(a) {
	for (let i = a.length - 1; i > 0; i--) {
		const j = Math.floor(Math.random() * (i + 1));
		[a[i], a[j]] = [a[j], a[i]];
	}
	
	return a
}
