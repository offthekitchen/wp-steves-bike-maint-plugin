/**
 * Displays a particular section for a given bike 
 *
 * @param {string} sectionName - the name of the section to be displayed
 * @param {number} bikeId - the ID of the bike being displayed
 * @param {string} bikeName - the name of the bike being displayed
 */
showSection = (sectionName, bikeId, bikeName) => {
  const maintLog = document.getElementById('maintenance-log')
  const bikeList = document.getElementById('bike-list')
  const specsList = document.getElementById('specs-list')
  const entryRecords = document.getElementsByClassName('entry-record')
  const bikeRecords = document.getElementsByClassName(`bike-${bikeId}`)
  const maintToSpecsLink = document.getElementById('maint-to-specs-link')
  const specsToMaintLink = document.getElementById('specs-to-maint-link')
  const noRecords = document.getElementsByClassName('no-records')
  const maintTitleBikeName = document.getElementById('maintenance-log-bike-name')
  const specsTitleBikeName = document.getElementById('specs-list-bike-name')

  if (sectionName != 'bikes') {
    // Hide all entries
    for (let i = 0; i < entryRecords.length; i++) {
      entryRecords[i].style.display = 'none'
    }

    // Hide the No Records messages
    for (let i = 0; i < noRecords.length; i++) {
      noRecords[i].style.visibility = 'hidden'
    }

    // Show all the records for this bike
    for (let i = 0; i < bikeRecords.length; i++) {
      bikeRecords[i].style.display = 'flex'
    }
  }

  switch (sectionName) {
    case 'maintenance':
      bikeList.style.display = 'none'
      specsList.style.display = 'none'
      maintLog.style.display = 'block'
      maintTitleBikeName.innerHTML = bikeName
      newAction = `showSection('specs', ${bikeId}, '${bikeName}')`
      maintToSpecsLink.setAttribute('onclick', newAction)
      break
    case 'specs':
      bikeList.style.display = 'none'
      maintLog.style.display = 'none'
      specsList.style.display = 'block'
      specsTitleBikeName.innerHTML = bikeName
      newAction = `showSection('maintenance', ${bikeId}, '${bikeName}')`
      specsToMaintLink.setAttribute('onclick', newAction)
      break
    case 'bikes':
      specsList.style.display = 'none'
      maintLog.style.display = 'none'
      bikeList.style.display = 'block'
      break
    default:
      console.error('NO SECTION NAME')
  }

  // After the appropriate records are hidden and displayed, use visibility to determine if there are records showing 
  if (sectionName != 'bikes') {
    if (checkForVisibleRecords(bikeRecords, sectionName)) {
      for (var i = 0; i < noRecords.length; i++) {
        noRecords[i].style.visibility = 'hidden'
      }
    } else {
      for (var i = 0; i < noRecords.length; i++) {
        noRecords[i].style.visibility = 'visible'
      }
    }
  }
}

/**
 * Checks if there are any records visible for a specific section
 *
 * @param {array} records - all records for all bikes
 * @param {string} sectionName - the name of the section in question
 * @returns {boolean} 
 */
checkForVisibleRecords = (records, sectionName) => {
  let entryClass = ''
  switch (sectionName) {
    case 'maintenance':
      entryClass = 'maintenance-entry'
      break
    case 'specs':
      entryClass = 'spec-entry'
      break
    case 'bikes':
      break
    default:
      console.error('NO SECTION NAME')
  }
  for (var i = 0; i < records.length; i++) {
    let style = window.getComputedStyle(records[i])
    if (
      style.display != 'none' &&
      style.visibility != 'hidden' &&
      records[i].classList.contains(entryClass)
    )
      return true
  }
  return false
}
